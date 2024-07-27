<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingTripay;
use App\Models\Transaksi\Invoice;
use App\Models\Global\ConvertNoHp;
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

    public function requestTransaksi($method, $data_inv, $inv, $icon)
    {
        $setting_tripay = SettingTripay::first();
        if ($setting_tripay) {
            $nomorhp = '0' . $data_inv->input_hp;
            $email = strtolower(str_replace(' ', '', $data_inv->input_nama)) . '@gmail.com';

            $apiKey       = $setting_tripay->tripay_apikey;
            $privateKey   = $setting_tripay->tripay_privatekey;
            $merchantCode = $setting_tripay->tripay_kode_merchant;
            $merchantRef  = $data_inv->inv_id;
            $amount       = $data_inv->inv_total;

            $data = [
                'method'         => $method,
                'merchant_ref'   => $merchantRef,
                'amount'         => $amount,
                'customer_name'  => $data_inv->inv_nama,
                'customer_email' => $email,
                'customer_phone' => $nomorhp,
                'order_items'    => [
                    [
                        'sku'         => $inv,
                        'name'        => 'Tagihan No. Invoice ' . $inv,
                        'price'       => $amount,
                        'quantity'    => 1,
                        'image_url'   => $icon,
                    ]
                ],
                'return_url'   => 'https://ovallapp.com/client/home',
                'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
                'signature'    => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
                // 'signature'    => hash_hmac('sha256', $merchantCode . $merchantRef . $datapel->upd_total, $privateKey)
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($data),
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            return $response ? $response : $error;
        }
    }

    // dd($nomorhp);
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
            return $response ? $response : $error;
        }
    }
}
