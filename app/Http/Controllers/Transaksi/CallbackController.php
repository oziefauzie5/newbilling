<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingTripay;
use App\Models\PSB\Registrasi;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\Paid;
use App\Models\Transaksi\Transaksi;
use Carbon\Carbon;
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
                ->where('inv_status', '!=', 'PAID')
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
                    $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
                        ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                        ->where('inv_id', $data->merchant_ref)
                        ->first();
                    $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
                    $cek_trx = Transaksi::whereDate('created_at', $tgl_bayar)->where('trx_kategori', 'INVOICE')->first();
                    #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
                    #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran
                    $inv0_tagih = Carbon::create($data_pelanggan->reg_tgl_tagih)->addMonth(1)->toDateString();
                    $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
                    $inv0_jt_tempo = Carbon::create($data_pelanggan->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString();
                    $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                    if ($data_pelanggan->reg_inv_control == 0) {
                        $reg['reg_tgl_jatuh_tempo'] = $inv0_jt_tempo;
                        $reg['reg_tgl_tagih'] = $inv0_tagih0;
                    } else {
                        $reg['reg_tgl_jatuh_tempo'] = $inv1_jt_tempo;
                        $reg['reg_tgl_tagih'] = $inv1_tagih1;
                    }

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
                    $datas['inv_tgl_bayar'] = $tgl_bayar;
                    $datas['inv_status'] = $data->status;
                    Invoice::where('inv_id', $data->merchant_ref)->update($datas);

                    $data_lap['lap_id'] = 0;
                    $data_lap['lap_tgl'] = $tgl_bayar;
                    $data_lap['lap_inv'] = $data->merchant_ref;
                    $data_lap['lap_admin'] = 10;
                    $data_lap['lap_cabar'] = 'TRIPAY';
                    $data_lap['lap_debet'] = 0;
                    $data_lap['lap_kredit'] = $data->amount_received;
                    $data_lap['lap_adm'] = 0;
                    $data_lap['lap_jumlah_bayar'] = 0;
                    $data_lap['lap_keterangan'] = $data_pelanggan->inv_nama;
                    $data_lap['lap_akun'] = 1;
                    $data_lap['lap_idpel'] = $data_pelanggan->inv_idpel;
                    $data_lap['lap_jenis_inv'] = "INVOICE";
                    $data_lap['lap_status'] = 0;
                    $data_lap['lap_img'] = "-";

                    Laporan::create($data_lap);
                    $reg['reg_status'] = 'PAID';
                    Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);

                    if ($cek_trx) {
                        $data_trx['trx_admin'] = 'SYSTEM';
                        $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                        $data_trx['trx_qty'] = $cek_trx->trx_qty + 1;
                        $data_trx['trx_total'] = $cek_trx->trx_total + $data_pelanggan->inv_total;
                        Transaksi::whereDate('created_at', $tgl_bayar)->update($data_trx);
                    } else {
                        $data_trx['trx_admin'] = 'SYSTEM';
                        $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                        $data_trx['trx_qty'] = 1;
                        $data_trx['trx_total'] = $data_pelanggan->inv_total;
                        Transaksi::whereDate('created_at', $tgl_bayar)->create($data_trx);
                    }

                    $router = Router::whereId($data_pelanggan->reg_router)->first();
                    $ip =   $router->router_ip . ':' . $router->router_port_api;
                    $user = $router->router_username;
                    $pass = $router->router_password;
                    $API = new RouterosAPI();
                    $API->debug = false;

                    if ($API->connect($ip, $user, $pass)) {
                        $cek_secret = $API->comm('/ppp/secret/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_secret) {
                            $API->comm('/ppp/secret/set', [
                                '.id' => $cek_secret[0]['.id'],
                                'profile' => $data_pelanggan->paket_nama,
                            ]);
                            $cek_status = $API->comm('/ppp/active/print', [
                                '?name' => $data_pelanggan->reg_username,
                            ]);
                            if ($cek_status) {
                                $API->comm('/ppp/active/remove', [
                                    '.id' =>  $cek_status['0']['.id'],
                                ]);
                            }
                        } else {
                            $API->comm('/ppp/secret/add', [
                                'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                                'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                                'service' => 'pppoe',
                                'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                                'comment' =>  $reg['reg_tgl_jatuh_tempo'] == '' ? '' : $reg['reg_tgl_jatuh_tempo'],
                                'disabled' => 'no',
                            ]);
                        }
                    }

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
