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
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));

        $unp = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_jenis_tagihan', 'FREE')->get();

        foreach ($unp as $d) {
            Pesan::create([

                'ket' => 'pengurus',
                'status' => '0',
                'layanan' => 'CS',
                'pesan_id_site' => '1',
                'nama' => $d->input_nama,
                'target' => $d->input_hp,
                'pesan' => 'Terima kasih 🙏
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
*OVALL FIBER*',
            ]);

            Invoice::where('inv_id', $d->inv_id)->update([
                'inv_cabar' => 'TUNAI',
                'inv_akun' => '2',
                'inv_reference' => '-',
                'inv_payment_method' => 'TUNAI',
                'inv_status' => 'PAID',
                'inv_tgl_bayar' => $data['now'],
                'inv_total' => '0',
            ]);
            SubInvoice::where('subinvoice_id', $d->inv_id)->update([
                'subinvoice_harga' => '0',
                'subinvoice_ppn' => '0',
                'subinvoice_total' => '0',
            ]);

            Registrasi::where('reg_idpel', $d->inv_idpel)->update([
                'reg_tgl_tagih' => Carbon::create($d->inv_tgl_jatuh_tempo)->addMonth(1)->addDay(-2)->toDateString(),
                'reg_tgl_jatuh_tempo' => Carbon::create($d->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString(),
                'reg_status' => 'PAID',
            ]);
        }
    }
}
