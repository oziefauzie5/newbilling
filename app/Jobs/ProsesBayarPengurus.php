<?php

namespace App\Jobs;

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
        $unp = Invoice::join('input_data', 'input_data.id', '=', 'invocies.inv_idpel')->where('inv_status', '!=', 'PAID')->where('inv_jenis_tagihan', 'FREE')->get();

        foreach ($unp as $d) {
            Invoice::where('inv_id', $d->inv_id)->update([
                'inv_status' => 'PAID',
                'inv_total' => '0',
            ]);
            SubInvoice::where('subinvoive_id', $d->inv_id)->update([
                'subinvoice_harga' => '0',
                'subinvoice_ppn' => '0',
                'subinvoice_total' => '0',
            ]);
            Registrasi::where('reg_idpel', $d->inv_idpel)->update([
                'reg_status' => 'PAID',
            ]);

            $pesan_group['ket'] = 'pengurus';
            $pesan_group['status'] = '0';
            $pesan_group['target'] = $d->input_hp;
            $pesan_group['pesan'] = '
Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $d->reg_nolayanan . '
Pelanggan : ' . $d->input_nama . '
Invoice : *' . $d->inv_id . '*
Profil : ' . $d->inv_profile . '
Total : *Rp ' . number_format($d->inv_total) . '*
Channel : TUNAI
Tanggal lunas : ' . date('Y-m-d H:m:s', strtotime(Carbon::now())) . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*';
            Pesan::create($pesan_group);
        }
    }
}
