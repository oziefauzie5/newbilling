<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PSB\Registrasi;
use App\Models\Pesan\Pesan;
use Carbon\Carbon;
use App\Http\Controllers\Global\GlobalController;

class NotifTelatPembayaran implements ShouldQueue
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
        $tanggal = date('Y-m-d', strtotime(Carbon::now()));
        $tagihan_kebelakang = Carbon::create($tanggal)->addMonth(-1)->toDateString();
        // dd($tagihan_kebelakang);
        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('reg_progres', '=', '5')
            ->where('reg_status', '!=', 'PAID')
            ->whereDate('reg_tgl_jatuh_tempo', '<', $tagihan_kebelakang)
            ->orderBy('reg_tgl_jatuh_tempo', 'ASC');
        $data_pelanggan = $query->count();

        $status = (new GlobalController)->whatsapp_status();
        if ($status->wa_status == 'Enable') {

            Pesan::create([
                'pesan_id_site' => '1',
                'layanan' => 'CS',
                'ket' => 'notif',
                'status' => '0',
                'target' => '120363162052425277@g.us',
                'nama' => 'PENGAMBILAN PERANGKAT',
                'pesan' => 'Pelanggan telat bayar diatas 30 hari = ' . $data_pelanggan . ' Pelanggan
Segera di Follow Up kembali.

Terimakasih atas kerjasama nya',
            ]);
        } else {

            Pesan::create([
                'pesan_id_site' => '1',
                'layanan' => 'CS',
                'ket' => 'notif',
                'status' => '10',
                'target' => '120363162052425277@g.us',
                'nama' => 'PENGAMBILAN PERANGKAT',
                'pesan' => 'Pelanggan telat bayar diatas 30 hari = ' . $data_pelanggan . ' Pelanggan
Segera di Follow Up kembali.

Terimakasih atas kerjasama nya',
            ]);
        }
    }
}
