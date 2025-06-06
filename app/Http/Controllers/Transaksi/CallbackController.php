<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingTripay;
use App\Models\Pesan\Pesan;
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
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\PSB\ApiController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Mitra\MutasiSales;
use App\Models\PSB\FtthFee;
use App\Models\Transaksi\SubInvoice;
use App\Models\User;
use Illuminate\Support\Facades\Session;

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
            $nama_user = 'SYSTEM';
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
                        ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
                        ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                        ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
                        ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
                        ->where('invoices.corporate_id',Session::get('corp_id'))
                        ->where('inv_id', $data->merchant_ref)
                        ->select([
                            'invoices.*',
                            'registrasis.reg_idpel',
                            'registrasis.reg_layanan',
                            'registrasis.reg_username',
                            'registrasis.reg_nolayanan',
                            'registrasis.reg_password',
                            'input_data.input_nama',
                            'pakets.paket_nama',
                            'routers.*',
                        ])
                        ->first();
                        

                $tgl_bayar = date('Y-m-d H:i:s', strtotime(Carbon::now()));

                    $now = Carbon::now();
                    $month = $now->format('m');
                    $year = $now->format('Y');



            $date1 = Carbon::createFromDate($data_pelanggan->inv_tgl_jatuh_tempo); // start date
            $valid_date = Carbon::parse($date1)->toDateString();
            $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
            $today = new \DateTime();
            $today->setTime(0, 0, 0);

            $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
            $match_date->setTime(0, 0, 0);

            $diff = $today->diff($match_date);
            $diffDays = (int)$diff->format("%R%a");

            $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo)); #new
            $hari_tgl_tagih = date('d', strtotime($data_pelanggan->reg_tgl_tagih)); #new

            $inv0_tagih = Carbon::create($year . '-' . $month . '-' . $hari_tgl_tagih)->addMonth(1)->toDateString(); #new
            $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
            $inv0_jt_tempo = Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString(); #new



            # diffDays < -0 artinya jika pelanggan melakukan pembayaran sebelum jatuh tempo.
            #Jika pelanggan melakukan pembayaran sebelum jatuh tempo, maka tanggal jatuh tempo tidak berubah.
            # diffDays > -0 artinya jika pelanggan melakukan pembayaran setelah jatuh tempo.
            # Jika pelanggan melakukan pembayaran lewat dari jatuh tempo, maka tanggal jatuh tempo akan berubah ke tanggal pelanggan melakukan pembayaran.
            $cek_hari_bayar = date('d', strtotime($tgl_bayar));
            if ($diffDays < -0) {
                # Cek tanggal pembayaran.
                # Jika Pelanggan melakukan pembayaran di atas tanggal 24 maka, tanggal jatuh tempo akan berubah ketanggal 1 bulan berikutnya 
                if ($cek_hari_bayar >= 25) {
                    #Tambah 1 bulan dari tgl pembeyaran
                    #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
                    // dd('Bayar di atas tgl 25');
                    $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                    $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
                } else {
                    $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                    $if_tgl_bayar = $tgl_bayar;
                    // dd('Bayar di bawah tgl 25');
                }
            } else {

                if ($cek_hari_bayar >= 25) {
                    #Tambah 1 bulan dari tgl pembeyaran
                    #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
                    $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                    $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
                    // dd('Bayar tepat waktu namun di atas tgl 25');
                } else {
                    $inv1_tagih = Carbon::create($data_pelanggan->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                    $if_tgl_bayar = $tgl_bayar;
                    // dd('pembayaran tepat waktu dibawah tgl 25');
                }
            }

            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran

            
            if ($data_pelanggan->reg_inv_control == 0) {
                $reg['reg_tgl_jatuh_tempo'] = $inv0_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv0_tagih0;
            } else {
                $reg['reg_tgl_jatuh_tempo'] = $inv1_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv1_tagih1;
            }

            // $admin_user = Auth::user()->id;

            $akun = SettingAkun::where('akun_type','TRIPAY')->where('corporate_id',Session::get('corp_id'))->select('akun_nama','id')->first();
            $user = User::where('name','TRIPAY')->where('corporate_id',Session::get('corp_id'))->select('name','id')->first();
               $api_payment = (new ApiController)->Api_payment_ftth($data_pelanggan,$nama_user,$reg);
            if($api_payment == 0)
            {

                   


            $datas['inv_cabar'] = 'TRIPAY';
            $datas['inv_admin'] = $user->id;
            $datas['inv_akun'] = $akun->id;
            $datas['inv_reference'] = $data->reference;
            $datas['inv_payment_method'] = $data->payment_method;
            $datas['inv_payment_method_code'] = $data->payment_method_code;
            $datas['inv_total_amount'] = $data->total_amount;
            $datas['inv_fee_merchant'] = $data->fee_merchant;
            $datas['inv_fee_customer'] = $data->fee_customer;
            $datas['inv_total_fee'] = $data->total_fee;
            $datas['inv_amount_received'] = $data->amount_received;
            $datas['inv_tgl_bayar'] = $if_tgl_bayar;
            $datas['inv_bukti_bayar'] = '-';
            $datas['inv_status'] = 'PAID';
            // dd($sum_fee);
            Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_id', $data->merchant_ref)->update($datas);
            $sum_fee = FtthFee::where('corporate_id',Session::get('corp_id'))->where('fee_idpel',$data_pelanggan->reg_idpel)->sum('reg_fee');
            $sumppn = SubInvoice::where('subinvoice_id', $data->merchant_ref)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn'); #hitung total ppn invoice
            $sumharga = SubInvoice::where('subinvoice_id', $data->merchant_ref)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga'); #hitung total harga invoice
            $sumhbph_uso = SubInvoice::where('subinvoice_id', $data->merchant_ref)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_bph_uso'); #hitung total bph_uso invoice
            $diskon = $data_pelanggan->inv_diskon;
            $total_inv = $sumharga + $sumppn - $diskon;
           
        
            
            #CEK JUMLAH INVOICE
            $cek_count_inv = Invoice::where('corporate_id',Session::get('corp_id'))
            ->where('inv_idpel', $data_pelanggan->inv_idpel)
            ->count();
            
            if($cek_count_inv >= 2){
                $mitra = FtthFee::join('mitra_settings','mitra_settings.mts_user_id','=','ftth_fees.reg_mitra')
                                    ->where('ftth_fees.fee_idpel',$data_pelanggan->inv_idpel)
                                    ->where('ftth_fees.corporate_id',Session::get('corp_id'))
                                    ->get();
                if($mitra){
                    $fee_mitra = $sum_fee  ?? '0';
                    foreach($mitra as $mit){
                        MutasiSales::create([
                            'mutasi_sales_mitra_id' => $mit->reg_mitra ?? '0',
                            'mutasi_sales_idpel' => $data_pelanggan->reg_idpel ?? '0',
                            'mutasi_sales_admin' => $admin_user ?? '0',
                            'mutasi_sales_type' => 'Credit',
                            'mutasi_sales_deskripsi' => $data_pelanggan->input_nama ?? '0',
                            'mutasi_sales_jumlah' => $mit->reg_fee ?? '0',
                            'mutasi_sales_inv_id' => $data->merchant_ref ?? '0',
                            'corporate_id' => Session::get('corp_id'),
                        ]);
                    }
                }
            } else {
                $fee_mitra = '0';
            }
            $lap['lap_id'] = time();
            $lap['corporate_id'] =Session::get('corp_id');
            $lap['lap_inv'] = $data->merchant_ref;
            
            $lap['lap_fee_mitra'] = $fee_mitra ?? '0';
            $lap['lap_ppn'] = $sumppn ?? '0';
            $lap['lap_bph_uso'] = $data_pelanggan->reg_bph_uso ?? '0';
            $lap['lap_pokok'] = $total_inv - $sumhbph_uso - $sumppn - $fee_mitra ?? '0';
            $lap['lap_jumlah'] = $total_inv ?? '0';
            $lap['lap_admin'] = 'TRIPAY';
            $lap['lap_akun'] = $akun;
            $lap['lap_keterangan'] = 'INV-'.$data->merchant_ref.' | '.$data_pelanggan->input_nama;
            $lap['lap_jenis_inv'] = 'Credit';
            $lap['lap_status'] = 0;
            Laporan::create($lap);
           
            $reg['reg_status'] = 'PAID';

            Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);

            $status = (new GlobalController)->whatsapp_status();
            if($status){
                if ($status->wa_status == 'Enable') {
                    $pesan_group['status'] = '0';
                } else {
                    $pesan_group['status'] = '10';
                }
            } else{
                $pesan_group['status'] = '10';
            }

            $pesan_group['pesan_id_site'] = '1';
            $pesan_group['corporate_id'] = Session::get('corp_id');
            $pesan_group['layanan'] = 'CS';
            $pesan_group['ket'] = 'payment';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['nama'] = $data_pelanggan->input_nama;
            $pesan_group['pesan'] = '
Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $data_pelanggan->reg_nolayanan . '
Pelanggan : ' . $data_pelanggan->input_nama . '
Invoice : *' . $data_pelanggan->inv_id . '*
Paket : ' . $data_pelanggan->paket_nama . '
Total : *Rp' . number_format($data_pelanggan->inv_total) . '*

Tanggal lunas : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
Layanan sudah aktif dan dapat digunakan sampai dengan *' . $reg['reg_tgl_jatuh_tempo'] . '*

BY : ' . $nama_user . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*'.Session::get('app_brand').'*';
            Pesan::create($pesan_group);
                $notifikasi = array(
                                'pesan' => 'Berhasil melakukan pembayaran',
                                'alert' => 'success',
                            );
                            return redirect()->route('admin.inv.sub_invoice', ['id' => $data->merchant_ref])->with($notifikasi);
            }  else if($api_payment == 1){
                // $notifikasi = array(
                //                 'pesan' => 'Berhasil melakukan pembayaran. Pelanggan sedang tidak aktif',
                //                 'alert' => 'success',
                //             );
                //             return redirect()->route('admin.inv.sub_invoice', ['id' => $data->merchant_ref])->with($notifikasi);
            } else if($api_payment == 3){
                $notifikasi = array(
                        'pesan' => 'Pembayaran tidak berhasil. Router Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.inv.sub_invoice', ['id' => $data->merchant_ref])->with($notifikasi);
            }
//                     $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
//                         ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
//                         ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
//                         ->where('invoice.corporate_id',Session::get('corp_id'))
//                         ->where('invoice.inv_id', $data->merchant_ref)
//                         ->first();




//                     $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));

//                     $now = Carbon::now();
//                     $month = $now->format('m');
//                     $year = $now->format('Y');


//                     $sum_trx = Transaksi::where('corporate_id',Session::get('corp_id'))->where('trx_jenis', 'Invoice')->whereDate('created_at', $tgl_bayar)->sum('trx_debet');
//                     $count_trx = Transaksi::where('corporate_id',Session::get('corp_id'))->where('trx_jenis', 'Invoice')->whereDate('created_at', $tgl_bayar)->sum('trx_qty');
//                     #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
//                     #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran

//                     $date1 = Carbon::createFromDate($data_pelanggan->inv_tgl_jatuh_tempo); // start date
//                     $valid_date = Carbon::parse($date1)->toDateString();
//                     $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
//                     $today = new \DateTime();
//                     $today->setTime(0, 0, 0);

//                     $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
//                     $match_date->setTime(0, 0, 0);

//                     $diff = $today->diff($match_date);
//                     $diffDays = (int)$diff->format("%R%a");


//                     $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo)); #new
//                     $hari_tgl_tagih = date('d', strtotime($data_pelanggan->reg_tgl_tagih)); #new

//                     $inv0_tagih = Carbon::create($year . '-' . $month . '-' . $hari_tgl_tagih)->addMonth(1)->toDateString(); #new
//                     $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
//                     $inv0_jt_tempo = Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString(); #new


//                     # diffDays < -0 artinya jika pelanggan melakukan pembayaran sebelum jatuh tempo.
//                     #Jika pelanggan melakukan pembayaran sebelum jatuh tempo, maka tanggal jatuh tempo tidak berubah.
//                     # diffDays > -0 artinya jika pelanggan melakukan pembayaran setelah jatuh tempo.
//                     # Jika pelanggan melakukan pembayaran lewat dari jatuh tempo, maka tanggal jatuh tempo akan berubah ke tanggal pelanggan melakukan pembayaran.
//                     $cek_hari_bayar = date('d', strtotime($tgl_bayar));
//                     if ($diffDays < -0) {
//                         # Cek tanggal pembayaran.
//                         # Jika Pelanggan melakukan pembayaran di atas tanggal 24 maka, tanggal jatuh tempo akan berubah ketanggal 1 bulan berikutnya 
//                         if ($cek_hari_bayar >= 25) {
//                             #Tambah 1 bulan dari tgl pembeyaran
//                             #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
//                             // dd('Bayar di atas tgl 25');
//                             $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
//                             $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
//                             $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
//                             $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
//                             $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
//                         } else {
//                             $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
//                             $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
//                             $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
//                             $if_tgl_bayar = $tgl_bayar;
//                             // dd('Bayar di bawah tgl 25');
//                         }
//                     } else {
//                         if ($cek_hari_bayar >= 25) {
//                             #Tambah 1 bulan dari tgl pembeyaran
//                             #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
//                             $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
//                             $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
//                             $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
//                             $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
//                             $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
//                             // dd('Bayar tepat waktu namun di atas tgl 25');
//                         } else {
//                             $inv1_tagih = Carbon::create($data_pelanggan->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString();
//                             $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
//                             $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
//                             $if_tgl_bayar = $tgl_bayar;
//                             // dd('pembayaran tepat waktu dibawah tgl 25');
//                         }
//                     }

//                     #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
//                     #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran

//                     if ($data_pelanggan->reg_inv_control == 0) {
//                         $reg['reg_tgl_jatuh_tempo'] = $inv0_jt_tempo;
//                         $reg['reg_tgl_tagih'] = $inv0_tagih0;
//                     } else {
//                         $reg['reg_tgl_jatuh_tempo'] = $inv1_jt_tempo;
//                         $reg['reg_tgl_tagih'] = $inv1_tagih1;
//                     }

//                     $datas['inv_cabar'] = 'TRIPAY';
//                     $datas['inv_admin'] = 'SYSTEM';
//                     $datas['inv_akun'] = '1';
//                     $datas['inv_reference'] = $data->reference;
//                     $datas['inv_payment_method'] = $data->payment_method;
//                     $datas['inv_payment_method_code'] = $data->payment_method_code;
//                     $datas['inv_total_amount'] = $data->total_amount;
//                     $datas['inv_fee_merchant'] = $data->fee_merchant;
//                     $datas['inv_fee_customer'] = $data->fee_customer;
//                     $datas['inv_total_fee'] = $data->total_fee;
//                     $datas['inv_amount_received'] = $data->amount_received;
//                     $datas['inv_tgl_bayar'] = $if_tgl_bayar;
//                     $datas['inv_status'] = $data->status;
//                     Invoice::where('inv_id', $data->merchant_ref)->update($datas);

//                     $data_lap['lap_id'] = time();
//                     $data_lap['lap_tgl'] = $if_tgl_bayar;
//                     $data_lap['lap_inv'] = $data->merchant_ref;
//                     $data_lap['lap_admin'] = 10;
//                     $data_lap['lap_cabar'] = 'TRIPAY';
//                     $data_lap['lap_debet'] = 0;
//                     $data_lap['lap_kredit'] = $data->amount_received;
//                     $data_lap['lap_fee_lingkungan'] = $data_pelanggan->reg_dana_kas;
//                     $data_lap['lap_fee_kerja_sama'] = $data_pelanggan->reg_dana_kerjasama;
//                     $data_lap['lap_fee_marketing'] = $data_pelanggan->reg_fee;
//                     $data_lap['lap_ppn'] = $data_pelanggan->reg_ppn;
//                     $data_lap['lap_adm'] = 0;
//                     $data_lap['lap_jumlah_bayar'] = 0;
//                     $data_lap['lap_keterangan'] = $data_pelanggan->inv_nama;
//                     $data_lap['lap_akun'] = 1;
//                     $data_lap['lap_idpel'] = $data_pelanggan->inv_idpel;
//                     $data_lap['lap_jenis_inv'] = "INVOICE";
//                     $data_lap['lap_status'] = 0;
//                     $data_lap['lap_img'] = "-";

//                     Laporan::create($data_lap);

//                     #CEK BULAN PEMASANGAN
//                     $bulan_pasang = date('Y-m', strtotime($data_pelanggan->reg_tgl_pasang));
//                     $bulan_bayar = date('Y-m', strtotime($if_tgl_bayar));
//                     if ($bulan_pasang != $bulan_bayar) {
//                         if ($data_pelanggan->reg_fee > 0) {
//                             $data_biaya = SettingBiaya::first();
//                             $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
//                             $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

//                             $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
//                             $mutasi_sales['smt_admin'] = 10;
//                             $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
//                             $mutasi_sales['smt_tgl_transaksi'] = $if_tgl_bayar;
//                             $mutasi_sales['smt_kategori'] = 'PENDAPATAN';
//                             $mutasi_sales['smt_deskripsi'] = $data_pelanggan->input_nama;
//                             $mutasi_sales['smt_cabar'] = '2';
//                             $mutasi_sales['smt_kredit'] = $data_biaya->biaya_sales_continue;
//                             $mutasi_sales['smt_debet'] = 0;
//                             $mutasi_sales['smt_saldo'] = $total;
//                             $mutasi_sales['smt_biaya_adm'] = 0;
//                             $mutasi_sales['smt_status'] = 0;
//                             MutasiSales::create($mutasi_sales);
//                         }
//                     }


//                     $reg['reg_status'] = 'PAID';
//                     Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);

//                     if ($count_trx == '0') {
//                         $data_trx['trx_kategori'] = 'Pendapatan';
//                         $data_trx['trx_jenis'] = 'Invoice';
//                         $data_trx['trx_admin'] = 'System';
//                         $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
//                         $data_trx['trx_qty'] = 1;
//                         $data_trx['trx_debet'] = $data_pelanggan->inv_total;
//                         Transaksi::where('trx_jenis', 'Invoice')->create($data_trx);
//                     } else {

//                         $i = '1';
//                         $data_trx['trx_qty'] = $count_trx + $i;
//                         $data_trx['trx_debet'] = $sum_trx + $data_pelanggan->inv_total;
//                         Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $if_tgl_bayar)->update($data_trx);
//                     }

//                     $status = (new GlobalController)->whatsapp_status();

//                     if ($status->wa_status == 'Enable') {
//                         $pesan_group['status'] = '0';
//                     } else {
//                         $pesan_group['status'] = '10';
//                     }

//                     $pesan_group['pesan_id_site'] = '1';
//                     $pesan_group['layanan'] = 'CS';
//                     $pesan_group['ket'] = 'payment';
//                     $pesan_group['target'] = $data_pelanggan->input_hp;
//                     $pesan_group['nama'] = $data_pelanggan->input_nama;
//                     $pesan_group['pesan'] = '
// Terima kasih 🙏
// Pembayaran invoice sudah kami terima
// *************************
// No.Layanan : ' . $data_pelanggan->inv_nolayanan . '
// Pelanggan : ' . $data_pelanggan->inv_nama . '
// Invoice : *' . $data_pelanggan->inv_id . '*
// Profil : ' . $data_pelanggan->inv_profile . '
// Total : *Rp' . $data_pelanggan->inv_total . '*

// Tanggal lunas : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
// Layanan sudah aktif dan dapat digunakan sampai dengan *' . $reg['reg_tgl_jatuh_tempo'] . '*

// BY : ' . $nama_user . '
// *************************
// --------------------
// Pesan ini bersifat informasi dan tidak perlu dibalas
// *'.Session::get('app_brand').'*';
//                     Pesan::create($pesan_group);


//                     $router = Router::whereId($data_pelanggan->reg_router)->first();
//                     $ip =   $router->router_ip . ':' . $router->router_port_api;
//                     $user = $router->router_username;
//                     $pass = $router->router_password;
//                     $API = new RouterosAPI();
//                     $API->debug = false;

//                     if ($API->connect($ip, $user, $pass)) {
//                         $cek_secret = $API->comm('/ppp/secret/print', [
//                             '?name' => $data_pelanggan->reg_username,
//                         ]);
//                         if ($cek_secret) {
//                             $API->comm('/ppp/secret/set', [
//                                 '.id' => $cek_secret[0]['.id'],
//                                 'profile' => $data_pelanggan->paket_nama,
//                                 'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
//                                 'disabled' => 'no',
//                             ]);
//                             $cek_status = $API->comm('/ppp/active/print', [
//                                 '?name' => $data_pelanggan->reg_username,
//                             ]);
//                             if ($cek_status) {
//                                 $API->comm('/ppp/active/remove', [
//                                     '.id' =>  $cek_status['0']['.id'],
//                                 ]);
//                             }
//                         } else {
//                             $API->comm('/ppp/secret/add', [
//                                 'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
//                                 'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
//                                 'service' => 'pppoe',
//                                 'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
//                                 'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
//                                 'disabled' => 'no',
//                             ]);
//                         }
//                     }

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
