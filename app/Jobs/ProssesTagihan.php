<?php

namespace App\Jobs;

use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->whereDate('inv_tgl_tagih', '<=', $data['now'])
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_jenis_tagihan', '!=', 'FREE')
            ->first();

        if ($data_pelanggan) {

            $pesan_group['ket'] = 'isolir otomatis';
            $pesan_group['status'] = '0';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['pesan'] = '
Pelanggan Yth.
Terima kasih sudah menjadi pelanggan setia kami,
berikut kami sampaikan tanggal jatuh tempo dan rincian tagihan bulan ini :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->inv_nama . '
Invoice : ' . $data_pelanggan->inv_id . '
Periode : ' . $data_pelanggan->inv_periode . '
Total tagihan : *' . number_format($data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama) . '*
Pembayaran paling lambat : ' . date('d-m-Y', strtotime($data_pelanggan->reg_tgl_jatuh_tempo)) . '

*PELUNASAN OTOMATIS*
Silahkan login ke aplikasi client area *https://ovallapp.com*
Tagihan otomatis lunas setelah melakukan pembayaran, jika menggunakan QRIS, Virtual account, Indomaret, Alfamart dan lain lain.
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*


';
        }
    }
}
