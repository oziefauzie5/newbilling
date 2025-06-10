<?php

namespace App\Jobs;

use App\Http\Controllers\Global\GlobalController;
use App\Models\Pesan\Pesan;
use App\Models\PSB\Registrasi;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Aplikasi\Corporate;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;

class ProssesIsolir implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
    

               $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
               ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
               ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
               ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
               ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
               ->whereDate('invoices.inv_tgl_isolir', '<=', $data['now'])
                ->where('invoices.inv_status', '=', 'SUSPEND')
               ->select([
                   'invoices.*',
                //    'invoices.corporate_id as corp_id',
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
               ->first();

               $corp = Corporate::where('id', $data_pelanggan->corporate_id)->first();

        $sumppn = SubInvoice::where('subinvoice_id', $data_pelanggan->inv_id)->where('corporate_id',$corp->id)->sum('subinvoice_ppn'); #hitung total ppn invoice
        $sumharga = SubInvoice::where('subinvoice_id', $data_pelanggan->inv_id)->where('corporate_id',$corp->id)->sum('subinvoice_harga'); #hitung total harga invoice
        $diskon = $data_pelanggan->inv_diskon;
        $total_inv = $sumharga + $sumppn - $diskon;
        if ($data_pelanggan) {
            $status = SettingWhatsapp::where('corporate_id',$corp->id)->first();;
            if ($status->wa_status == 'Enable') {
                $pesan_group['status'] = '0';
            } else {
                $pesan_group['status'] = '10';
            }
            $cek_pesan = Pesan::where('target', $data_pelanggan->input_hp)->where('status', 0)->where('ket', 'isolir otomatis')->count();
            if ($cek_pesan == 0) {
                
                $pesan_group['corporate_id'] = $data_pelanggan->corporate_id;
                $pesan_group['layanan'] = 'CS';
                $pesan_group['ket'] = 'isolir otomatis';
                $pesan_group['target'] = $data_pelanggan->input_hp;
                $pesan_group['nama'] = $data_pelanggan->input_nama;
                $pesan_group['pesan'] = '
Pelanggan yang terhormat,
Kami informasikan bahwa layanan internet anda saat ini *TERISOLIR* otomatis oleh sistem!!!, kami mohon maaf atas ketidaknyamanannya
Untuk dapat digunakan kembali, segera lakukan pembayaran atas tagihan sebagai berikut :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->input_nama . '
Invoice : '.$data_pelanggan->inv_id.'
Jatuh Tempo : ' . $data_pelanggan->inv_tgl_jatuh_tempo . '
Total tagihan :Rp. *' . number_format($total_inv) . '*

Untuk melihat detail layanan dan cara pembayaran tagihan, bisa melalui link berikut *'.$corp->corp_url.'*
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
';
                
            }


            $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
            $user = $data_pelanggan->router_username;
            $pass = $data_pelanggan->router_password;
            $API = new RouterosAPI();
            $API->debug = false;
            if ($API->connect($ip, $user, $pass)) {
            if ($data_pelanggan->reg_layanan == 'PPP') {
                    $cek_secret = $API->comm('/ppp/secret/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
                        Pesan::create($pesan_group);
                        $cek_status = $API->comm('/ppp/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ppp/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                    }
            } elseif ($data_pelanggan->reg_layanan == 'HOTSPOT') {
                    $cek_secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
                        Pesan::create($pesan_group);
                        $cek_status = $API->comm('/ip/hotspot/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ip/hotspot/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
