<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingTripay;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Paid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {

        $setting_tripay = SettingTripay::first();

        $privateKey = $setting_tripay->tripay_privatekey;

        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== (string) $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return Response::json([
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ]);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ]);
        }
        // $data_pelanggan = (new globalController)->data_langganan($data->reference);
        // $saldo_lh = (new globalController)->laporan_harian('0');
        // $total_lh = $saldo_lh + $data->amount_received;
        // dd($total_lh);
        $status = strtoupper((string) $data->status);


        if ($data->is_closed_payment === 1) {
            $invoice = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
                ->where('inv_id', $data->merchant_ref)
                // ->where('upd_idpel', $data->reference)
                ->where('inv_status', '=', 'UNPAID')
                ->first();
            // dd($invoice);

            if (!$invoice) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $data->merchant_ref,
                ]);
            }
            switch ($status) {
                case 'PAID':

                    // $paid['id_unpaid'] = $data->merchant_ref; #No referensi transaksi. Contoh: T000100000000XHDFTR disini saya gunakan unpaid_id
                    // $paid['idpel_unpaid'] = $invoice->inv_idpel;
                    // $paid['reference'] = $data->reference; #No referensi/invoice merchant disini saya gunakan id pelanggan
                    // $paid['payment_method'] = $data->payment_method; #Channel pembayaran. Contoh: BRI Virtual Account
                    // $paid['payment_method_code'] = $data->payment_method_code; #Kode channel pembayaran. Contoh: BRIVA
                    // $paid['total_amount'] = $data->total_amount; #Jumlah pembayaran yang dibayar pelanggan
                    // $paid['fee_merchant'] = $data->fee_merchant; #Jumlah biaya yang dikenakan pada merchant
                    // $paid['fee_customer'] = $data->fee_customer; #Jumlah biaya yang dikenakan pada customer
                    // $paid['total_fee'] = $data->total_fee; #Jumlah biaya fee_merchant + 
                    // $paid['amount_received'] = $data->amount_received; #Jumlah bersih yang diterima merchant. Dihitung dari total_amount - (fee_merchant + fee_customer)
                    // $paid['is_closed_payment'] = $data->is_closed_payment; #Tipe pembayaran
                    // $paid['status'] = $data->status; #Status transaksi
                    // $paid['paid_at'] = $data->paid_at; #Timestamp waktu pembayaran sukses
                    // $paid['admin'] = '0'; #User Admin
                    // $paid['akun'] = '1'; #Cara Bayar
                    // $paid['note'] = $data->note; #Catatan
                    // dd($paid);
                    // Paid::create($paid);



                    // laporanharian::create([
                    //     'lh_id' => $data->merchant_ref,
                    //     'lh_admin' => '0',
                    //     'lh_deskripsi' => 'Invoice ' . $data->merchant_ref . ' ( ' . $data_pelanggan->nama . ' ) Diskon ' . number_format($invoice->upd_diskon) . ' PPN ' . number_format($invoice->subinvoice_ppn) . ' via : Tripay',
                    //     'lh_qty' => '1',
                    //     'lh_debet' => '0',
                    //     'lh_kredit' => $data->amount_received,
                    //     'lh_saldo' => $total_lh,
                    //     'lh_status' => '0',
                    //     'lh_kategori' => 'PEMBAYARAN',
                    //     'lh_akun' => '1',
                    // ]);

                    $datas['inv_cabar'] = 'TRIPAY';
                    $datas['inv_admin'] = 'SYSTEM';
                    $datas['inv_reference'] = $data->reference;
                    $datas['inv_payment_method'] = $data->payment_method;
                    $datas['inv_payment_method_code'] = $data->payment_method_code;
                    $datas['inv_total_amount'] = $data->total_amount;
                    $datas['inv_fee_merchant'] = $data->fee_merchant;
                    $datas['inv_fee_customer'] = $data->fee_customer;
                    $datas['inv_total_fee'] = $data->total_fee;
                    $datas['inv_amount_received'] = $data->amount_received;
                    $datas['inv_tgl_bayar'] = $data->paid_at;
                    $datas['inv_status'] = $data->status;
                    Invoice::where('inv_id', $data->merchant_ref)->update($datas);
                    break;

                case 'EXPIRED':
                    Invoice::where('inv_id', $data->merchant_ref)->update(['inv_status' => 'UNPAID']);
                    break;

                case 'FAILED':
                    Invoice::where('inv_id', $data->merchant_ref)->update(['inv_status' => 'UNPAID']);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }

            return Response::json(['success' => true]);
        }
    }
}
