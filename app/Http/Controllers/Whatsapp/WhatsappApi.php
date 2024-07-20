<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use CURLFile;
use Illuminate\Http\Request;

class WhatsappApi extends Controller
{
    public function update_group_list()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/fetch-group',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: 5S3y7udf2Uh+Sbt99A3u'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function send_message()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => '120363028776966861@g.us',
                'message' => 'test message to {name} as {var1}',
                // 'url' => 'https://md.fonnte.com/images/wa-logo.png',
                // 'filename' => 'filename',
                // 'schedule' => 0,
                // 'typing' => false,
                // 'delay' => '5',
                'countryCode' => '62',
                // 'file' => new CURLFile("localfile.jpg"),
                'location' => '-6.60857950392001, 106.755854',
                // 'followup' => 0,
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: 5S3y7udf2Uh+Sbt99A3u'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;
    }
}
