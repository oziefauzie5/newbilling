<?php

namespace App\Jobs;

use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Pesan\Pesan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
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
        $cek_pesan = Pesan::where('status', '0')->count();
        if ($cek_pesan) {
            $pesan = Pesan::where('status', '0')->orderBy('created_at', 'ASC')->first();
            if ($pesan->layanan == 'CS') {
                $whatsapp = SettingWhatsapp::where('wa_status', 'Enable')->where('wa_nama', 'CS')->first();
            } elseif ($pesan->layanan == 'CS1') {
                $whatsapp = SettingWhatsapp::where('wa_status', 'Enable')->where('wa_nama', 'CS1')->first();
            } elseif ($pesan->layanan == 'NOC') {
                $whatsapp = SettingWhatsapp::where('wa_status', 'Enable')->where('wa_nama', 'NOC')->first();
            }

            if ($pesan->file) {
                $data = array(
                    'target' => $pesan->target,
                    'message' => $pesan->pesan,
                    'countryCode' => '62',
                    'url' => $pesan->file,
                );
            } else {
                $data = array(
                    'target' => $pesan->target,
                    'message' => $pesan->pesan,
                    'countryCode' => '62',
                );
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $whatsapp->wa_url . '/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $whatsapp->wa_key . ''
                ),
            ));


            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $mesage['status'] = 'Gagal';
            } else {
                echo $response;
                $mesage['status'] = 'Done';
            }
            // $mesage['status'] = 'test';
            Pesan::where('id', $pesan->id)->update($mesage);
        }
    }
}
