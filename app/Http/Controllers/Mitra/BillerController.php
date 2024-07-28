<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\PSB\Registrasi;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\SubInvoice;
use App\Models\Transaksi\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillerController extends Controller
{
    // public function pembayaran()
    // {

    //     $data = array(
    //         'tittle' => 'TRANSAKSI',
    //         'data' => DB::table('unpaids')
    //             ->join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
    //             ->join('pelanggans', 'pelanggans.idpel', '=', 'unpaids.upd_idpel')
    //             ->join('pakets', 'pakets.paket_id', '=', 'pelanggans.paket')
    //             ->where('unpaids.upd_status', '=', 'UNPAID')
    //             ->where('unpaids.upd_status', '=', 'UNPAID')
    //             ->get(),
    //     );
    //     return view('mitra/index', $data);
    // }

    public function getpelanggan(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $query =  DB::table('invoices')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_id', '=', $id)
            ->orWhere('inv_nolayanan', '=', $id)
            ->first();
        // return response()->json($query->inv_status);
        if ($query->inv_status == 'PAID') {
            $data['data'] = $query->inv_status;
        } else {
            $data['data'] = $query;
            $data['biller'] = MitraSetting::first();
            $data['saldo'] = (new GlobalController)->total_mutasi($admin_user);
            $data['sumharga'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_harga');
            $data['sumppn'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_ppn');
        }
        return response()->json($data);
    }
    // public function getDataLunas(Request $request, $id)
    // {
    //     $admin_user = Auth::user()->id;
    //     $data_bayar = array(
    //         'data' => DB::table('unpaids')
    //             ->join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
    //             ->join('pelanggans', 'pelanggans.idpel', '=', 'unpaids.upd_idpel')
    //             ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'unpaids.upd_id')
    //             ->where('unpaids.upd_status', '=', 'PAID')
    //             ->where('unpaids.upd_id', '=', $id)
    //             ->orWhere('unpaids.upd_nolayanan', '=', $id)
    //             ->first(),
    //         'sumharga' => SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_harga'),
    //         'sumppn' => SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn'),
    //         'biller' => MitraSetting::first(),
    //         'saldo' => (new globalController)->total_mutasi($admin_user),
    //     );
    //     return response()->json($data_bayar);
    // }
    public function print(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $data['admin'] = Auth::user()->name;
        $data['data'] = DB::table('invoices')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'invoices.inv_akun')
            ->join('users', 'users.id', '=', 'invoices.inv_admin')
            ->where('invoices.inv_id', '=', $id)
            ->orWhere('invoices.inv_nolayanan', '=', $id)
            ->where('invoices.inv_status', '=', 'PAID')
            ->first();
        $data['sumharga'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_harga');
        $data['sumppn'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_ppn');
        $data['datainvoice'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->get();
        $data['biller'] = MitraSetting::first();
        $data['saldo'] = (new globalController)->total_mutasi($admin_user);


        // dd($data['data']);
        return view('biller/print', $data);
    }

    public function mutasi()
    {
        $admin_user = Auth::user()->id;
        $data['tittle'] = 'MITRA';
        $query =  DB::table('mutasis')
            ->orderBy('mutasis.id', 'DESC')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
            ->where('mutasis.mt_mts_id', '=', $admin_user);
        $data['mutasi'] = $query->get();

        return view('biller/mutasi', $data);
    }
    public function mutasi_pdf(Request $request)
    {
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;

        $data['start_date'] =  $request->start_date;
        $data['end_date'] =  $request->end_date;

        $query =  DB::table('mutasis')->select('mutasis.*', 'mutasis.created_at as tgl', 'mitra_settings.*')
            ->orderBy('tgl', 'ASC')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
            ->where('mutasis.mt_mts_id', '=', $data['admin_user'])
            ->whereDate('mutasis.created_at', '>=', $data['start_date'])
            ->whereDate('mutasis.created_at', '<=', $data['end_date']);
        $data['mutasi'] = $query->get();
        $data['saldo'] = (new globalController)->total_mutasi($data['admin_user']);

        $pdf = App::make('dompdf.wrapper');
        $html = view('biller/pdf', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Mutasi.pdf');
    }
    public function payment()
    {
        $data['tittle'] = 'Payment';
        return view('biller/payment', $data);
    }

    public function index()
    {
        $month = Carbon::now()->format('m');
        $admin_user = Auth::user()->id;
        $data['nama'] = Auth::user()->name;
        $data['saldo'] = (new globalController)->total_mutasi($admin_user);
        $data['biaya_adm'] = DB::table('mutasis')->whereRaw('extract(month from created_at) = ?', [$month])->where('mt_mts_id', $admin_user)->sum('mt_biaya_adm');


        $data['data'] = Invoice::where('inv_status', '=', 'PAID')->where('inv_admin', $admin_user)->get();
        return view('biller/index', $data);
    }

    public function bayar(Request $request, $id)
    {
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));

        $admin_user = Auth::user()->id; #ID USER
        $mitra = MitraSetting::where('mts_user_id', $admin_user)->where('mts_limit_minus', '!=', '0')->first();
        $query_trx = Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar);
        $count_trx = $query_trx->count();
        $sum_trx = $query_trx->sum('trx_total');

        // return response()->json($cek_trx);



        $saldo_mutasi = (new GlobalController)->total_mutasi($admin_user); #Cek saldo mutasi terlebih dahulu sebelum melakukan pemabayaran
        $cek_tagihan = (new GlobalController)->data_tagihan($id); #cek data tagihan pembayaran
        $sumharga = SubInvoice::where('subinvoice_id', $cek_tagihan->inv_id)->sum('subinvoice_harga'); #hitung total harga invoice
        $sumppn = SubInvoice::where('subinvoice_id', $cek_tagihan->inv_id)->sum('subinvoice_ppn'); #hitung total ppn invoice
        $total_bayar = $sumharga + $sumppn - $cek_tagihan->inv_diskon;



        if ($mitra) {

            $saldo_mitra =  '-' . $mitra->mts_limit_minus <= ($saldo_mutasi - $total_bayar);
        } else {
            $saldo_mitra =  $saldo_mutasi > $total_bayar;
        }

        if ($saldo_mitra) {
            $biller = MitraSetting::where('mts_user_id', $admin_user)->first(); #mengambil biaya admni biller pada table mitra_setting

            $data_pelanggan = (new GlobalController)->data_tagihan($id);
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
            $saldo = (new globalController)->total_mutasi($admin_user);
            $pembayaran = $sumharga + $sumppn - $data_pelanggan->inv_diskon;
            $total = $saldo - $pembayaran; #SALDO MUTASI = DEBET - KREDIT

            $datas['inv_cabar'] = 'TUNAI';
            $datas['inv_admin'] = $admin_user;
            $datas['inv_akun'] = '2';
            $datas['inv_reference'] = '-';
            $datas['inv_payment_method'] = 'TUNAI';
            $datas['inv_payment_method_code'] = '-';
            $datas['inv_total_amount'] = $data_pelanggan->inv_total + $biller->mts_komisi;
            $datas['inv_fee_merchant'] = 0;
            $datas['inv_fee_customer'] = $biller->mts_komisi;
            $datas['inv_total_fee'] = $biller->mts_komisi;
            $datas['inv_amount_received'] = $data_pelanggan->inv_total;
            $datas['inv_tgl_bayar'] = $tgl_bayar;
            $datas['inv_status'] = 'PAID';
            Invoice::where('inv_id', $data_pelanggan->inv_id)->update($datas);

            $data_lap['lap_id'] = 0;
            $data_lap['lap_tgl'] = $tgl_bayar;
            $data_lap['lap_inv'] = $data_pelanggan->inv_id;
            $data_lap['lap_admin'] = $admin_user;
            $data_lap['lap_cabar'] = 'TUNAI';
            $data_lap['lap_debet'] = 0;
            $data_lap['lap_kredit'] = $data_pelanggan->inv_total;
            $data_lap['lap_adm'] = $biller->mts_komisi;
            $data_lap['lap_jumlah_bayar'] = $data_pelanggan->inv_total + $biller->mts_komisi;;
            $data_lap['lap_keterangan'] = $data_pelanggan->inv_nama;
            $data_lap['lap_akun'] = '2';
            $data_lap['lap_idpel'] = $data_pelanggan->inv_idpel;
            $data_lap['lap_jenis_inv'] = "INVOICE";
            $data_lap['lap_status'] = 0;
            Laporan::create($data_lap);
            $reg['reg_status'] = 'PAID';
            Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);




            Mutasi::create([
                'mt_admin' => $admin_user,
                'mt_mts_id' => $admin_user,
                'mt_kategori' => 'PEMBAYARAN',
                'mt_deskripsi' => $data_pelanggan->input_nama . ' INVOICE-' . $data_pelanggan->inv_id,
                'mt_debet' => $data_pelanggan->inv_total,
                'mt_kredit' => '0',
                'mt_saldo' => $total,
                'mt_biaya_adm' => $biller->mts_komisi,
                'mt_cabar' => '2',
            ]);


            if ($count_trx == 0) {
                $data_trx['trx_kategori'] = 'PEMASUKAN';
                $data_trx['trx_jenis'] = 'INVOICE';
                $data_trx['trx_admin'] = 'SYSTEM';
                $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                $data_trx['trx_qty'] = 1;
                $data_trx['trx_total'] = $data_pelanggan->inv_total;
                Transaksi::where('trx_jenis', 'INVOICE')->create($data_trx);
            } else {

                $data_trx['trx_qty'] = $count_trx + 1;
                $data_trx['trx_total'] = $sum_trx + $data_pelanggan->inv_total;
                Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->update($data_trx);
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
                        $notifikasi = array(
                            'pesan' => 'Berhasil melakukan pembayaran',
                            'alert' => 'success',
                        );
                        return response()->json($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Berhasil melakukan pembayaran.',
                            'alert' => 'success',
                        );
                        return response()->json($notifikasi);
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

                    $notifikasi = array(
                        'pesan' => 'Berhasil melakukan pembayaran',
                        'alert' => 'success',
                    );
                    return response()->json($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Berhasil melakukan pembayaran. Namun Maaf..!! Router Disconnected',
                    'alert' => 'success',
                );
                return response()->json($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Transaksi Gagal. Saldo anda tidak cukup',
                'alert' => 'error',
            );
            return response()->json($notifikasi);
        }
    }
}
