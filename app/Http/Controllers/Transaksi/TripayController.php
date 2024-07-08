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

            $response = json_decode($response)->data;
            // dd($response);

            return $response ? $response : $error;
        }
    }

    public function requestTransaksi($method, $inv, $icon)
    {
        $setting_tripay = SettingTripay::first();
        if ($setting_tripay) {
            $invoice = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
                ->where('invoices.inv_id', '=', $inv)->first();
            $apiKey = $setting_tripay->tripay_apikey;
            $privateKey = $setting_tripay->tripay_privatekey;
            $merchantCode = $setting_tripay->tripay_kode_merchant;

            $merchantRef  = $invoice->inv_id;


            $data = [
                'method'         => $method,
                'merchant_ref'   => $invoice->inv_id,
                'amount'         => $invoice->inv_total,
                'customer_name'  => $invoice->input_nama,
                'customer_email' => $invoice->input_email,
                'customer_phone' => '0' . $invoice->input_hp,
                'order_items'    => [
                    [
                        "sku" => $inv,
                        'name'        => 'Tagihan No. Invoice ' . $inv,
                        'price'       => $invoice->inv_total,
                        'quantity'    => 1,
                        "image_url" => $icon,

                    ],
                ],
                'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
                'signature'    => hash_hmac('sha256', $merchantCode . $merchantRef . $invoice->inv_total, $privateKey)
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
            // dd($response);

            return $response ?: $error;
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
