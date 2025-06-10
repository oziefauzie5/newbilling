<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\PSB\Registrasi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Global\GlobalController;
use Illuminate\Support\Facades\Session;

class GenerateInvoice extends Controller
{
    public function generate_invoice()
    {
        $now = Carbon::now();

        $last_day = $now->format('t');
        $month = $now->format('m');
        // $month = '04';
        $year = $now->format('Y');
        $th = $now->format('y');
        $addonemonth =  date('m',strtotime(Carbon::create(Carbon::now())->addMonth(1)->toDateString()));
        // $addonemonth =  07;

        // $test = date($year . '-' . $month . '-'.$last_day);

        
        
        // dd($addonemonth);
        

        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_progres', '>=', 3)
            ->where('registrasis.reg_progres', '<=', 5)
            ->whereMonth('registrasis.reg_tgl_jatuh_tempo', '!=', $addonemonth)
            // ->where('reg_nolayanan', '=', 24031948416)
            ->get();

        $swaktu = SettingWaktuTagihan::first();
        $i = 1;
        // dd($data_pelanggan);
        $no_iv = (new GlobalController)->no_inv();

        foreach ($data_pelanggan as $dp) {
            if ($dp->reg_status == 'PAID') {
                
                $inv_id = $month . $th . sprintf('%04d', $i++);
                $hari_jt_tempo = date('d', strtotime($dp->reg_tgl_jatuh_tempo));

                $cek_hari = date('d', strtotime($dp->reg_tgl_jatuh_tempo));
                if ($cek_hari == 23) {
                    $jeda_waktu = '0';
                    $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                    $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                } elseif ($cek_hari == 24) {
                    $jeda_waktu = '0';
                    $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                    $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                } else {
                    $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
                    $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                    $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                }
                // echo '<table><tr><td>' . $inv_id . '</td><td>' . $dp->reg_nolayanan . '</td><td>' . $dp->input_nama . '</td><td>' . $dp->reg_status . '</td><td>' . date('d-m-Y', strtotime($dp->reg_tgl_jatuh_tempo)) . '</td></tr></table>';
                // dd($tgl_jt_tempo);



                $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($jeda_waktu)->toDateString();
                Invoice::create([
                    'inv_id' => $inv_id,#generate
                    // 'inv_id' => $no_iv, #add invoice
                    'corporate_id' => Session::get('corp_id'),
                    'inv_status' => 'UNPAID',
                    'inv_idpel' => $dp->reg_idpel,
                    'inv_nolayanan' => $dp->reg_nolayanan,
                    'inv_jenis_tagihan' => $dp->reg_jenis_tagihan,
                    // 'inv_profile' => $dp->paket_nama,
                    'inv_tgl_tagih' => $hari_tgl_tagih,
                    'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
                    'inv_tgl_isolir' => $tgl_isolir,
                    'inv_periode' => $periode1blan,
                    'inv_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                ]);

                SubInvoice::create(
                    [
                        'subinvoice_id' => $inv_id, #generate
                        // 'subinvoice_id' => $no_iv, #add invoice
                        'corporate_id' => Session::get('corp_id'),
                        'subinvoice_deskripsi' => $dp->paket_nama . ' ( ' . $periode1blan . ' )',
                        'subinvoice_harga' => $dp->reg_harga + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                        'subinvoice_ppn' => $dp->reg_ppn,
                        'subinvoice_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                        'subinvoice_qty' => 1,
                        'subinvoice_status' => 0,
                    ]
                );
                // dd('test');
            } else {
                $data_invoice = Invoice::where('inv_idpel', $dp->reg_idpel)->where('inv_status', '!=', 'PAID')->get();
                // dd($dp->reg_idpel);

                foreach ($data_invoice as $inv) {
                    $inv_id = $inv->inv_id;
                    #Jika pelanggan belum melakukan pembayaran pada bulan sebelumnya, maka jatuh tempo pelanggan akan berubah ke tanggal 2 saat generate invoice baru
                   
                   
                    // $hari_jt_tempo = date('d', strtotime($dp->reg_tgl_jatuh_tempo));
                    // $cek_hari = date('d', strtotime($dp->reg_tgl_jatuh_tempo));
                    // if ($cek_hari == 23) {
                    //     $jeda_waktu = '0';
                    //     $hari_tgl_tagih = date($year . '-' . $month . '-02', strtotime($dp->reg_tgl_tagih));
                    //     $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    //     $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                    // } elseif ($cek_hari == 24) {
                    //     $jeda_waktu = '0';
                    //     $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                    //     $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    //     $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                    // } else {
                        $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
                        $hari_tgl_tagih = date($year . '-' . $month . '-02', strtotime($dp->reg_tgl_tagih));
                        $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-02')->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-02')->addMonth(1)->toDateString()));
                        // $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                        $tgl_jt_tempo = date($year . '-' . $month . '-02', strtotime($dp->reg_tgl_jatuh_tempo));
                    // }



                    $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($jeda_waktu)->toDateString();

                    echo '<table><tr><td>' . $inv->inv_id . '</td><td>' . $inv->inv_nolayanan . '</td><td>' . $dp->input_nama . '</td><td>' . $inv->inv_status . '</td></tr></table>';

                    Invoice::where('inv_id', $inv->inv_id)->update([
                        'inv_status' => 'ISOLIR',
                        'inv_tgl_tagih' => $hari_tgl_tagih,
                        'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
                        // 'inv_tgl_isolir' => $tgl_isolir,
                        'inv_periode' => $periode1blan,
                        'inv_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                    ]);
                    SubInvoice::where('subinvoice_id', $inv->inv_id)->update(
                        [
                            'subinvoice_deskripsi' => $dp->paket_nama . ' ( ' . $periode1blan . ' )',
                            'subinvoice_harga' => $dp->reg_harga + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                            'subinvoice_ppn' => $dp->reg_ppn,
                            'subinvoice_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                            'subinvoice_qty' => 1,
                            'subinvoice_status' => 0,
                        ]
                    );
                }
            }
        }
        $notifikasi = array(
            'pesan' => 'Generate Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.index')->with($notifikasi);
    }
}
