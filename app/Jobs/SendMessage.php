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
            $whatsapp = SettingWhatsapp::where('wa_nama', 'CUSTUMER SERVICE')->where('wa_status', 'Enable')->first();
            $pesan = Pesan::where('status', '0')->first();
            $body = array(
                "api_key" => $whatsapp->wa_key,
                "receiver" => $pesan->target,
                "data" => array("message" => $pesan->pesan)
            );

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $whatsapp->wa_url . "/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json",
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $mesage['status'] = 'Fail';
            } else {
                echo $response;
                $mesage['status'] = 'Done';
            }
            Pesan::where('id', $pesan->id)->update($mesage);
        }
    }
}
