<?php

namespace App\Jobs;

use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Corporate;
use App\Models\Pesan\Pesan;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;

class ProssesTagihan implements ShouldQueue
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
        // $data['now'] = date('Y-m-d', strtotime(2025 - 04 - 01));
        // $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        //     ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
        //     // ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
        //     // ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
        //     ->whereDate('inv_tgl_tagih', '=', $data['now'])
        //     ->where('inv_status', '!=', 'PAID')
        //     ->where('inv_jenis_tagihan', '!=', 'FREE')
        //     ->get();

         $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
               ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
               ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
               ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
               ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
               ->whereDate('invoices.inv_tgl_tagih', '=', $data['now'])
                ->where('invoices.inv_status', '!=', 'PAID')
                ->where('registrasis.reg_jenis_tagihan', '!=', 'FREE')
               ->select([
                   'invoices.*',
                   'registrasis.reg_idpel',
                   'registrasis.reg_layanan',
                   'registrasis.reg_username',
                   'registrasis.reg_nolayanan',
                   'registrasis.reg_password',
                   'input_data.input_nama',
                   'input_data.input_hp',
                   'pakets.paket_nama',
                   'routers.*',
               ])
               ->get();

            //    dd($data_pelanggan);

               
               $status = (new GlobalController)->whatsapp_status();
               foreach ($data_pelanggan as $key) {
                   $corp = Corporate::where('id', $key->corporate_id)->first();
                   $sumppn = SubInvoice::where('subinvoice_id', $key->inv_id)->where('corporate_id',$corp->id)->sum('subinvoice_ppn'); #hitung total ppn invoice
                   $sumharga = SubInvoice::where('subinvoice_id', $key->inv_id)->where('corporate_id',$corp->id)->sum('subinvoice_harga'); #hitung total harga invoice
                   $diskon = $key->inv_diskon;
                    $total_inv = $sumharga + $sumppn - $diskon;
                   if($status){
                       
                       if ($status->wa_status == 'Enable') {
                           
                Pesan::create([
                    'layanan' => 'CS',
                    'corporate_id' => $corp->id,
                    'ket' => 'tagihan',
                    'status' => '0',
                    'target' => $key->input_hp,
                    'nama' => $key->input_nama,
                    'pesan' => '
Pelanggan Yth.

Terima kasih sudah menjadi pelanggan setia kami,
berikut kami sampaikan tanggal jatuh tempo dan rincian tagihan bulan ini :

No.Layanan : ' . $key->reg_nolayanan . '
Pelanggan : ' . $key->input_nama . '
Invoice : ' . $key->inv_id . '
Periode : ' . $key->inv_periode . '
Total tagihan : *' . number_format($total_inv) . '*
Pembayaran paling lambat : ' . date('d/m/Y', strtotime($key->inv_tgl_jatuh_tempo)) . '

*PELUNASAN OTOMATIS*
Silahkan login ke link berikut *'.env('LINK_APK').'*
Tagihan otomatis lunas setelah melakukan pembayaran, jika menggunakan QRIS, Virtual account, Indomaret, Alfamart dan lain lain.
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas',
                ]);
            } else {

                Pesan::create([
                    'layanan' => 'CS',
                    'corporate_id' => $corp->id,
                    'ket' => 'tagihan',
                    'status' => '0',
                    'target' => $key->input_hp,
                    'nama' => $key->input_nama,
                    'pesan' => '
Pelanggan Yth.

Terima kasih sudah menjadi pelanggan setia kami,
berikut kami sampaikan tanggal jatuh tempo dan rincian tagihan bulan ini :

No.Layanan : ' . $key->reg_nolayanan . '
Pelanggan : ' . $key->input_nama . '
Invoice : ' . $key->inv_id . '
Periode : ' . $key->inv_periode . '
Total tagihan : *' . number_format($total_inv) . '*
Pembayaran paling lambat : ' . date('d/m/Y', strtotime($key->inv_tgl_jatuh_tempo)) . '

*PELUNASAN OTOMATIS*
Silahkan login ke link berikut *'.env('LINK_APK').'*
Tagihan otomatis lunas setelah melakukan pembayaran, jika menggunakan QRIS, Virtual account, Indomaret, Alfamart dan lain lain.
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas',
                ]);
            }
                
            }else {
                  Pesan::create([
                     'layanan' => 'CS',
                    'corporate_id' => $corp->id,
                    'ket' => 'tagihan',
                    'status' => '0',
                    'target' => $key->input_hp,
                    'nama' => $key->input_nama,
                    'pesan' => '
Pelanggan Yth.

Terima kasih sudah menjadi pelanggan setia kami,
berikut kami sampaikan tanggal jatuh tempo dan rincian tagihan bulan ini :

No.Layanan : ' . $key->reg_nolayanan . '
Pelanggan : ' . $key->input_nama . '
Invoice : ' . $key->inv_id . '
Periode : ' . $key->inv_periode . '
Total tagihan : *' . number_format($total_inv) . '*
Pembayaran paling lambat : ' . date('d/m/Y', strtotime($key->inv_tgl_jatuh_tempo)) . '

*PELUNASAN OTOMATIS*
Silahkan login ke link berikut *'.env('LINK_APK').'*
Tagihan otomatis lunas setelah melakukan pembayaran, jika menggunakan QRIS, Virtual account, Indomaret, Alfamart dan lain lain.
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas',
                ]);
            }
        }
    }
}
