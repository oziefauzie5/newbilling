<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingTripay;
use App\Models\Transaksi\Invoice;
use Illuminate\Http\Request;

class TripayController extends Controller
{
    public function getPaymentChannels()
    {

        $setting_tripay = SettingTripay::first();
        if ($setting_tripay) {

            $apiKey = $setting_tripay->tripay_apikey;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api/merchant/payment-channel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
            if (json_decode($response)->success == false) {
                $response = '';
            } else {
                $response = json_decode($response)->data;
            }
            return $response ? $response : $error;
        }
    }

    public function requestTransaksi($method, $inv, $icon)
    {
        $setting_tripay = SettingTripay::first();
        if ($setting_tripay) {

            $apiKey = $setting_tripay->tripay_apikey;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api/merchant/payment-channel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ));


            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);

            return $response ? $response : $error;
        }
    }

    public function detailsTransakasi($refrensi)
    {


        $setting_tripay = SettingTripay::first();
        if ($setting_tripay) {
            $apiKey = $setting_tripay->tripay_apikey;

            $payload = ['reference'    => $refrensi];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api/transaction/detail?' . http_build_query($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);
            $response = json_decode($response)->data;
            // dd($response);
            return $response ? $response : $error;
        }
    }
}
