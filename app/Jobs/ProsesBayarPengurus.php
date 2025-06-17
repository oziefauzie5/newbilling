<?php

namespace App\Jobs;

use App\Models\Aplikasi\Corporate;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Pesan\Pesan;
use App\Models\PSB\Registrasi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;

class ProsesBayarPengurus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
          $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

              $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
               ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
               ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
               ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
               ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
                ->where('invoices.inv_status', '!=', 'PAID')
                ->where('registrasis.reg_jenis_tagihan', 'FREE')
               ->select([
                   'invoices.*',
                   'registrasis.reg_idpel',
                   'registrasis.reg_layanan',
                   'registrasis.reg_username',
                   'registrasis.reg_nolayanan',
                   'registrasis.reg_password',
                   'registrasis.reg_bph_uso',
                   'registrasis.reg_ppn',
                   'registrasis.reg_kode_unik',
                   'registrasis.reg_harga',
                   'input_data.input_nama',
                   'input_data.input_hp',
                   'pakets.paket_nama',
                   'routers.*',
               ])
               ->get();

        // $unp = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
        //     ->where('inv_status', '!=', 'PAID')
        //     ->where('inv_jenis_tagihan', 'FREE')->get();
       

        
        
        foreach ($data_pelanggan as $d) {
            $corp = Corporate::where('id', $d->corporate_id)->first();
            $status = SettingWhatsapp::where('corporate_id',$corp->id)->first();
            if($status){
            if ($status->wa_status == 'Enable') {
                $status_wa = '0';
            } else {
                $status_wa = '10';
            }
            }else {
                $status_wa = '10';
            }
            // $cek_pesan = Pesan::where('target', $d->inpsut_hp)->where('status', 0)->where('ket', 'isolir otomatis')->count();
            // if ($cek_pesan == 0) {
            $akun = SettingAkun::where('akun_type','TUNAI')->where('corporate_id',$d->corporate_id)->select('akun_nama','id')->first();
                Pesan::create([
    
                    'ket' => 'pengurus',
                    'status' => $status_wa,
                    'layanan' => 'CS',
                    'corporate_id' => $d->corporate_id,
                    'nama' => $d->input_nama,
                    'target' => $d->input_hp,
                    'pesan' => 'Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $d->reg_nolayanan . '
Pelanggan : ' . $d->input_nama . '
Invoice : *' . $d->inv_id . '*
Paket : ' . $d->paket_nama . '
Total : *Rp ' . number_format($d->inv_total) . '*
Channel : TUNAI
Tanggal lunas : ' . date('Y-m-d H:m:s', strtotime(Carbon::now())) . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
',
                ]);
        //  dd($data_pelanggan);
            $cek_hari_bayar = date('d', strtotime($tgl_bayar));
            if ($cek_hari_bayar >= 25) {
                #Tambah 1 bulan dari tgl pembeyaran
                #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
                // dd('Bayar di atas tgl 25');
                $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
            } else {
                $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
                $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                $if_tgl_bayar = $tgl_bayar;
                // dd('Bayar di bawah tgl 25');
            }

            Invoice::where('inv_id', $d->inv_id)->update([
                'inv_cabar' => $akun->akun_nama,
                'inv_akun' => $akun->id,
                'inv_reference' => '-',
                'inv_payment_method' => 'TUNAI',
                'inv_status' => 'PAID',
                'inv_tgl_bayar' => $if_tgl_bayar,
                'inv_total' => '0',
            ]);
            SubInvoice::where('subinvoice_id', $d->inv_id)->update([
                'subinvoice_harga' => '0',
                'subinvoice_ppn' => '0',
                'subinvoice_total' => '0',
            ]);

            Registrasi::where('reg_idpel', $d->inv_idpel)->update([
                // 'reg_tgl_tagih' => Carbon::create($d->inv_tgl_jatuh_tempo)->addMonth(1)->addDay(-2)->toDateString(),
                'reg_tgl_tagih' => $inv1_tagih1,
                // 'reg_tgl_jatuh_tempo' => Carbon::create($d->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString(),
                'reg_tgl_jatuh_tempo' => $inv1_jt_tempo,
                'reg_status' => 'PAID',
            ]);
        }
    }
}
