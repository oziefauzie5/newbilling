<?php

namespace App\Jobs;

use App\Models\Pesan\Pesan;
use App\Models\PSB\Registrasi;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProssesIsolir implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->whereDate('inv_tgl_isolir', '<=', $data['now'])
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_status', '!=', 'ISOLIR')
            ->where('inv_jenis_tagihan', '!=', 'FREE')
            ->first();

        if ($data_pelanggan) {

            $pesan_group['ket'] = 'isolir otomatis';
            $pesan_group['status'] = '0';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['pesan'] = '
Pelanggan yang terhormat,
Kami informasikan bahwa layanan internet anda saat ini sedang di *ISOLIR* oleh sistem secara otomatis❗, kami mohon maaf atas ketidaknyamanannya
Agar dapat digunakan kembali dimohon untuk melakukan pembayaran tagihan sebagai berikut :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->inv_nama . '
Invoice : 013524
Jatuh Tempo : ' . $data_pelanggan->reg_tgl_jatuh_tempo . '
Total tagihan :Rp. *' . number_format($data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama) . '*

--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*
';
            Pesan::create($pesan_group);

            $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
            $user = $data_pelanggan->router_username;
            $pass = $data_pelanggan->router_password;
            $API = new RouterosAPI();
            $API->debug = false;
            if ($data_pelanggan->reg_layanan == 'PPP') {
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ppp/secret/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'comment' => 'ISOLIR OTOMATIS' == '' ? '' : 'ISOLIR OTOMATIS',
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
                        $cek_status = $API->comm('/ppp/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ppp/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                    }
                }
            } elseif ($data_pelanggan->reg_layanan == 'HOTSPOT') {
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'comment' => 'ISOLIR OTOMATIS' == '' ? '' : 'ISOLIR OTOMATIS',
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
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
