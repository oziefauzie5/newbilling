<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Barang\Barang;
use App\Models\Barang\SubBarang;
use App\Models\Barang\supplier;
use App\Models\Global\ConvertNoHp;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\Mitra\MutasiSales;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class BillerController extends Controller
{

    public function getpelanggan(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $query =  DB::table('invoices')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_id', '=', $id)
            ->orWhere('inv_nolayanan', '=', $id)
            ->orWhere('input_data.input_hp', '=', $id)
            ->orWhere('input_data.input_nama', '=', $id)
            ->latest('inv_tgl_jatuh_tempo')
            ->first();
        // return response()->json($query->inv_status);
        if ($query->inv_status == 'PAID') {
            $data['data'] = $query->inv_status;
        } else {
            $data['data'] = $query;
            $data['biller'] = MitraSetting::where('mts_user_id', $admin_user)->first();
            $data['saldo'] = (new GlobalController)->total_mutasi($admin_user);
            $data['sumharga'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_harga');
            $data['sumppn'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_ppn');
        }
        return response()->json($data);
    }

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
        $data['biller'] = MitraSetting::where('mts_user_id', $admin_user)->first();
        $data['saldo'] = (new globalController)->total_mutasi($admin_user);


        // dd($data['data']);
        return view('biller/print', $data);
    }

    public function mutasi()
    {
        $admin_user = Auth::user()->id;
        $data['tittle'] = 'MITRA';
        $query =  DB::table('mutasis')
            ->select('mutasis.*', 'mutasis.created_at as tgl_trx', 'mitra_settings.*')
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
        $tagihan_kedepan = Carbon::now()->addday(5)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();

        $data['tittle'] = 'Payment';
        $data['input_data'] = Invoice::select('input_data.*', 'input_data.id as idp', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            // ->whereDate('inv_tgl_jatuh_tempo', '>', $tagihan_kebelakang)
            ->whereDate('inv_tgl_jatuh_tempo', '<=', $tagihan_kedepan)
            ->where('inv_status', '!=', 'PAID')->get();

        return view('biller/payment', $data);
    }
    public function paymentbytagihan($inv_id)
    {
        $data['invoice_id'] = $inv_id;
        return view('biller/paymentbytagihan', $data);
    }

    public function index(Request $request)
    {
        $month = Carbon::now()->format('m');
        $bulan_lalu = date('m', strtotime(Carbon::create(Carbon::now())->addMonth(-1)->toDateString()));
        $tagihan_kedepan = Carbon::now()->addday(5)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();

        $admin_user = Auth::user()->id;
        $data['nama'] = Auth::user()->name;

        $role = (new globalController)->data_user($admin_user);
        $data['role'] = $role->name;

        $data['saldo'] = (new globalController)->total_mutasi($admin_user);
        $data['biaya_adm'] = DB::table('mutasis')->whereRaw('extract(month from created_at) = ?', [$month])->where('mt_mts_id', $admin_user)->sum('mt_biaya_adm');

        $data['q'] = $request->query('q');
        $query_isolir = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            // ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('reg_progres', '=', '5')
            ->where('reg_status', '!=', 'PAID')
            ->whereDate('reg_tgl_jatuh_tempo', '<', $tagihan_kebelakang)
            ->orderBy('reg_tgl_jatuh_tempo', 'ASC')
            ->where(function ($query_isolir) use ($data) {
                $query_isolir->Where('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query_isolir->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query_isolir->orWhere('reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            });
        $data['pengambilan_perangkat'] =  $query_isolir->get();

        // dd($data['pengambilan_perangkat']);
        $data['count_pengambilan_perangkat'] = $query_isolir->count();

        $data['data'] = Invoice::where('inv_status', '=', 'PAID')->where('inv_admin', $admin_user)->get();
        return view('biller/index', $data);
    }
    public function sales()
    {
        $user = (new GlobalController)->user_admin();
        $user_id = $user['user_id'];
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $data['komisi'] = (new globalController)->total_mutasi_sales($user_id);
        $m = date('m', strtotime(new Carbon()));
        $data['pencairan'] = MutasiSales::where('smt_user_id', $user_id)->whereMonth('created_at', $m)->sum('smt_debet');
        // dd($debet);
        $data['input_data'] = InputData::where('input_sales', $user_id)->where('input_status', 'INPUT DATA')->get();
        $data['registrasi'] = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_status', '<', '5')->get();
        // dd($data['pelanggan']);

        return view('biller/sales', $data);
    }
    public function sales_input()
    {
        $user = (new GlobalController)->user_admin();
        $user_id = $user['user_id'];
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $data['admin_user'] = (new GlobalController)->user_admin();

        return view('biller/sales_input', $data);
    }
    public function sales_store(Request $request)
    {

        $user = (new GlobalController)->user_admin();
        $id_cust = (new GlobalController)->idpel_();
        $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
        if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
            if (substr(trim($nomorhp2), 0, 3) == '+62') {
                $nomorhp2 = trim($nomorhp2);
            } elseif (substr($nomorhp2, 0, 1) == '0') {
                $nomorhp2 = '' . substr($nomorhp2, 1);
            }
        }
        $user_nama = $user['user_nama'];
        $user_id = $user['user_id'];
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        Session::flash('input_nama', ucwords($request->input_nama));
        Session::flash('input_hp', $request->input_hp);
        Session::flash('input_hp_2', $request->input_hp_2);
        Session::flash('input_ktp', $request->input_ktp);
        Session::flash('input_email', $request->input_email);
        Session::flash('input_alamat_ktp', ucwords($request->input_alamat_ktp));
        Session::flash('input_alamat_pasang', ucwords($request->input_alamat_pasang));
        Session::flash('input_sales', ucwords($request->input_sales));
        Session::flash('input_subseles', ucwords($request->input_subseles));
        Session::flash('input_password', Hash::make($request->input_hp));
        Session::flash('input_maps', $request->input_maps);
        Session::flash('input_keterangan', ucwords($request->input_keterangan));

        $request->validate([
            'input_ktp' => 'unique:input_data',
            'input_hp' => 'unique:input_data',
            'input_hp_2' => 'unique:input_data',
        ], [
            'input_ktp.unique' => 'Nomor Identitas sudah terdaftar',
            'input_hp.unique' => 'Nomor Whatsapp sudah terdaftar',
            'input_hp_2.unique' => 'Nomor Whatsapp cadangan sudah terdaftar',
        ]);
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

        $cek_nohp = InputData::where('input_hp', $nomorhp)->count();
        $cek_nohp2 = InputData::where('input_hp', $nomorhp2)->count();

        if ($cek_nohp == 0) {
            $input['input_tgl'] = $data['input_tgl'];
            $input['input_nama'] = ucwords($request->input_nama);
            $input['id'] = $id_cust;
            $input['input_ktp'] = $request->input_ktp;
            $input['input_hp'] = $nomorhp;
            $input['input_hp_2'] = $nomorhp2;
            $input['input_email'] = $request->input_email;
            $input['input_alamat_ktp'] = ucwords($request->input_alamat_ktp);
            $input['input_alamat_pasang'] = ucwords($request->input_alamat_pasang);
            $input['input_sales'] = $user_id;
            $input['input_subseles'] = ucwords($user_nama);
            $input['password'] = Hash::make($nomorhp);
            $input['input_maps'] = $request->input_maps;
            $input['input_status'] = 'INPUT DATA';
            $input['input_keterangan'] = $request->input_keterangan;
            // dd($input);
            InputData::create($input);
            $notifikasi = [
                'pesan' => 'Berhasil input data',
                'alert' => 'success',
            ];
            return redirect()->route('admin.biller.sales')->with($notifikasi);
        } else {
            $notifikasi = [
                'pesan' => 'Nomor Hp sudah terdaftar',
                'alert' => 'error',
            ];
            return redirect()->route('admin.biller.sales_input')->with($notifikasi);
        }
    }

    public function bayar(Request $request, $id)
    {

        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        $admin_user = Auth::user()->id; #ID USER
        $nama_user = Auth::user()->name; #NAMA USER
        $mitra = MitraSetting::where('mts_user_id', $admin_user)->where('mts_limit_minus', '!=', '0')->first();
        $sum_trx = Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->sum('trx_total');
        $count_trx = Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->sum('trx_qty');
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
            // return response()->json($data_pelanggan->inv_id);
            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran

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


            if ($diffDays < -0) {
                $cek_hari_bayar = date('d', strtotime($tgl_bayar));
                if ($cek_hari_bayar >= 25) {
                    #Tambah 1 bulan dari tgl pembeyaran
                    #Pembayaran di atas tanggal 25 maka akan di anggap bayar tgl 25
                    $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                    $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                } else {
                    $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                }
            } else {
                $inv1_tagih = Carbon::create($data_pelanggan->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString();
                $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
            }

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


            $data_lap['lap_id'] = time();
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
            $reg['reg_status'] = 'PAID';

            $mutasi['mt_admin'] = $admin_user;
            $mutasi['mt_mts_id'] = $admin_user;
            $mutasi['mt_kategori'] = 'PEMBAYARAN';
            $mutasi['mt_deskripsi'] = $data_pelanggan->input_nama . ' INVOICE-' . $data_pelanggan->inv_id;
            $mutasi['mt_debet'] = $data_pelanggan->inv_total;
            $mutasi['mt_kredit'] = '0';
            $mutasi['mt_saldo'] = $total;
            $mutasi['mt_biaya_adm'] = $biller->mts_komisi;
            $mutasi['mt_cabar'] = '2';

            $status = (new GlobalController)->whatsapp_status();

            if ($status->wa_status == 'Enable') {
                $pesan_group['status'] = '0';
            } else {
                $pesan_group['status'] = '10';
            }


            $pesan_group['ket'] = 'payment biller';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['nama'] = $data_pelanggan->input_nama;
            $pesan_group['pesan'] = '
Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $data_pelanggan->inv_nolayanan . '
Pelanggan : ' . $data_pelanggan->inv_nama . '
Invoice : *INV' . $data_pelanggan->inv_id . '*
Paket : ' . $data_pelanggan->inv_profile . '
Periode : ' . $data_pelanggan->inv_periode . '
Biaya adm : *Rp' . number_format($biller->mts_komisi) . '*
Total : *Rp' . number_format($biller->mts_komisi + $data_pelanggan->inv_total) . '*

Tanggal lunas : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
Layanan sudah aktif dan dapat digunakan sampai dengan *' . date('d-m-Y', strtotime($reg['reg_tgl_jatuh_tempo'])) . '*

BY : ' . $nama_user . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*';


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
                        'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                        'disabled' => 'no',
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
                        $i = '1';
                        $data_trx['trx_qty'] = $count_trx + $i;
                        $data_trx['trx_total'] = $sum_trx + $data_pelanggan->inv_total;
                        Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->update($data_trx);
                    }

                    if ($data_pelanggan->reg_fee > 0) {
                        $data_biaya = SettingBiaya::first();
                        $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                        $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                        $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                        $mutasi_sales['smt_admin'] = $admin_user;
                        $mutasi_sales['smt_kategori'] = 'PENDAPATAN';
                        $mutasi_sales['smt_deskripsi'] = $data_pelanggan->input_nama;
                        $mutasi_sales['smt_cabar'] = '2';
                        $mutasi_sales['smt_kredit'] = $data_biaya->biaya_sales_continue;
                        $mutasi_sales['smt_debet'] = 0;
                        $mutasi_sales['smt_saldo'] = $total;
                        $mutasi_sales['smt_biaya_adm'] = 0;
                        $mutasi_sales['smt_status'] = 0;
                        MutasiSales::create($mutasi_sales);
                    }

                    Laporan::create($data_lap);
                    Invoice::where('inv_id', $data_pelanggan->inv_id)->update($datas);
                    Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);
                    Mutasi::create($mutasi);
                    Pesan::create($pesan_group);
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
                        'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                        'disabled' => 'no',
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
                        $i = '1';
                        $data_trx['trx_qty'] = $count_trx + $i;
                        $data_trx['trx_total'] = $sum_trx + $data_pelanggan->inv_total;
                        Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->update($data_trx);
                    }
                    if ($data_pelanggan->reg_fee > 0) {
                        $data_biaya = SettingBiaya::first();
                        $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                        $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                        $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                        $mutasi_sales['smt_admin'] = $admin_user;
                        $mutasi_sales['smt_kategori'] = 'PENDAPATAN';
                        $mutasi_sales['smt_deskripsi'] = $data_pelanggan->input_nama;
                        $mutasi_sales['smt_cabar'] = '2';
                        $mutasi_sales['smt_kredit'] = $data_biaya->biaya_sales_continue;
                        $mutasi_sales['smt_debet'] = 0;
                        $mutasi_sales['smt_saldo'] = $total;
                        $mutasi_sales['smt_biaya_adm'] = 0;
                        $mutasi_sales['smt_status'] = 0;
                        MutasiSales::create($mutasi_sales);
                    }

                    Invoice::where('inv_id', $data_pelanggan->inv_id)->update($datas);
                    Laporan::create($data_lap);
                    Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);
                    Mutasi::create($mutasi);
                    Pesan::create($pesan_group);

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

    public function list_tagihan(Request $request)
    {
        $tagihan_kedepan = Carbon::now()->addday(5)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();


        // dd($tagihan_kebelakang);

        $pasang_bulan_ini = Carbon::now()->addMonth(-0)->format('Y-m-d');
        $pasang_bulan_lalu = Carbon::now()->addMonth(-1)->format('Y-m-d');
        $pasang_3_bulan_lalu = Carbon::now()->addMonth(-2)->format('Y-m-d');
        // $bulan_ini = date('m', strtotime(Carbon::now()));


        $data['data_bulan'] = $request->query('data_bulan');
        $data['data_inv'] = $request->query('data_inv');
        $data['q'] = $request->query('q');

        $query = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_status', '!=', 'PAID')
            ->whereDate('inv_tgl_jatuh_tempo', '>', $tagihan_kebelakang)
            ->whereDate('inv_tgl_jatuh_tempo', '<=', $tagihan_kedepan)
            ->orderBy('inv_tgl_jatuh_tempo', 'ASC')
            ->where(function ($query) use ($data) {
                $query->where('inv_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            });

        if ($data['data_bulan'] == "1") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_ini);
            $data['data_bulan'] = 'PELANGGAN BARU';
        } elseif ($data['data_bulan'] == "2") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_lalu);
            $data['data_bulan'] = 'PELANGGAN 2 BULAN';
        } elseif ($data['data_bulan'] == "3") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_3_bulan_lalu);
            $data['data_bulan'] = 'PELANGGAN 3 BULAN';
        }

        if ($data['data_inv'])
            $query->where('inv_status', '=', $data['data_inv']);


        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->get();
        $data['inv_count_unpaid'] = Invoice::where('inv_status', '=', 'UNPAID')->count();
        $data['inv_belum_lunas'] = Invoice::where('inv_status', '!=', 'PAID')->sum('inv_total');
        $data['inv_lunas'] = Invoice::where('inv_status', '=', 'PAID')->sum('inv_total');
        $data['inv_count_suspend'] = Invoice::where('inv_status', '=', 'SUSPEND')->count();
        $data['inv_count_isolir'] = Invoice::where('inv_status', '=', 'ISOLIR')->count();
        $data['inv_count_lunas'] = Invoice::where('inv_status', '=', 'PAID')->count();
        return view('biller/list_tagihan', $data);
    }

    public function biller_putus_berlanggan(Request $request, $idpel)
    {
        // dd('biller');
        $nama_admin = Auth::user()->name;
        // dd($progres);
        $tgl = date('Y-m-d H:m:s', strtotime(carbon::now()));
        $query =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('registrasis.reg_idpel', $idpel)->first();

        if ($request->status == 'PUTUS LANGGANAN') {
            $keterangan = 'PB - ' . strtoupper($query->input_nama);
            $progres = '100';
        } else {
            $keterangan = 'PS  - ' . strtoupper($query->input_nama);
            $progres = '90';
        }


        if ($query->reg_mac) {
            if ($query->reg_mac == $request->reg_mac) {

                $ip =   $query->router_ip . ':' . $query->router_port_api;
                $user = $query->router_username;
                $pass = $query->router_password;
                $API = new RouterosAPI();
                $API->debug = false;


                // dd($query);
                if ($API->connect($ip, $user, $pass)) {
                    $cek_status = $API->comm('/ppp/active/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($cek_status) {
                        $API->comm('/ppp/active/remove', [
                            '.id' => $cek_status[0]['.id'],
                        ]);
                    }
                    $cari_pel = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($cari_pel) {
                        $API->comm('/ppp/secret/remove', [
                            '.id' =>  $cari_pel['0']['.id']
                        ]);
                    }

                    $data = Invoice::where('inv_idpel', $idpel)->where('inv_status', '!=', 'PAID')->first();
                    if ($data) {
                        $data->delete();
                        SubInvoice::where('subinvoice_id', $data->inv_id)->delete();
                    }

                    // CEK BARANG 
                    $cek_subbarang = SubBarang::where('subbarang_mac', $request->reg_mac)->first();
                    $cek_suplier = supplier::where('supplier_nama', 'ONT')->first();
                    if ($cek_subbarang) {
                        // JIKA ONT ADA 
                        $update_barang['subbarang_status'] = '5';
                        $update_barang['subbarang_keluar'] = '0';
                        $update_barang['subbarang_stok'] = '1';
                        $update_barang['subbarang_mac'] = $request->reg_mac;
                        $update_barang['subbarang_nama'] = 'ONT';
                        $update_barang['subbarang_keterangan'] = $keterangan;
                        $update_barang['subbarang_deskripsi'] = 'Dalam Pengecekan';
                        $update_barang['subbarang_admin'] = $nama_admin;
                        SubBarang::where('subbarang_mac', $request->reg_mac)->update($update_barang);

                        SubBarang::create(
                            [
                                "id_subbarang" => mt_rand(100000, 999999),
                                "subbarang_idbarang" => '811170',
                                "subbarang_nama" => 'ADAPTOR',
                                "subbarang_keterangan" => $keterangan,
                                "subbarang_deskripsi" => 'Dalam Pengecekan',
                                "subbarang_ktg" => 'ADAPTOR',
                                "subbarang_qty" => 1,
                                "subbarang_keluar" => '0',
                                "subbarang_stok" => 1,
                                "subbarang_harga" => 0,
                                "subbarang_tgl_masuk" => $tgl,
                                "subbarang_status" => '5',
                                "subbarang_admin" => $nama_admin,
                            ]
                        );
                    } else {
                        // JIKA ONT TIDAK ADA
                        // BUAT DAFTAR ONT BARU
                        // BUAT DENGAN NAMA SUPLIER ONT TARIKAN
                        // CEK SUDAH ADA ATAU BELUM NAMA SUPLIER ONT TARIKAN



                        if ($cek_suplier) {
                            // JIKA SUPLIER ADA MAKA EKSEKUSI BUAT DAFTAR BARANG
                            $data['supplier'] = $cek_suplier->id_supplier;
                        } else {
                            // JIKA SUPLIER TIDAK ADA MAKA EKSEKUSI BUAT SUPLIER TERLEBIH DAHULU
                            $count = supplier::count();
                            if ($count == 0) {
                                $id_supplier = 1;
                            } else {
                                $id_supplier = $count + 1;
                            }
                            $id_supplier = '1' . sprintf("%03d", $id_supplier);

                            supplier::create([
                                'id_supplier' => $id_supplier,
                                'supplier_nama' => 'ONT',
                                'supplier_alamat' => 'Jl. Tampomas Perum. Alam Tirta Lestari Blok D5 No 06',
                                'supplier_tlp' => '081386987015',
                            ]);
                            $data['supplier'] = $id_supplier;
                        }

                        $cek_barang = Barang::where('id_supplier', $data['supplier'])->first();
                        if ($cek_barang) {
                            $id['subbarang_idbarang'] = $cek_barang->id_barang;
                        } else {

                            $id['subbarang_idbarang'] = mt_rand(100000, 999999);
                            Barang::create([
                                'id_barang' => $id['subbarang_idbarang'],
                                'id_trx' => '-',
                                'id_supplier' => $data['supplier'],
                                'barang_tgl_beli' => '1',
                            ]);
                        }

                        SubBarang::create(
                            [
                                "id_subbarang" => mt_rand(100000, 999999),
                                "subbarang_idbarang" => $id['subbarang_idbarang'],
                                "subbarang_nama" => 'ONT',
                                "subbarang_keterangan" => $keterangan,
                                "subbarang_deskripsi" => 'Dalam Pengecekan',
                                "subbarang_ktg" => 'ONT',
                                "subbarang_qty" => 1,
                                "subbarang_keluar" => '0',
                                "subbarang_stok" => 1,
                                "subbarang_harga" => 0,
                                "subbarang_tgl_masuk" => $tgl,
                                "subbarang_status" => '5',
                                "subbarang_mac" => $request->reg_mac,
                                "subbarang_admin" => $nama_admin,
                            ]
                        );

                        SubBarang::create(
                            [
                                "id_subbarang" => mt_rand(100000, 999999),
                                "subbarang_idbarang" => '811170',
                                "subbarang_nama" => 'ADAPTOR',
                                "subbarang_keterangan" => $keterangan,
                                "subbarang_deskripsi" => 'Dalam Pengecekan',
                                "subbarang_ktg" => 'ADAPTOR',
                                "subbarang_qty" => 1,
                                "subbarang_keluar" => '0',
                                "subbarang_stok" => 1,
                                "subbarang_harga" => 0,
                                "subbarang_tgl_masuk" => $tgl,
                                "subbarang_status" => '5',
                                "subbarang_admin" => $nama_admin,
                            ]
                        );
                    }

                    Registrasi::where('reg_idpel', $idpel)->update([
                        'reg_progres' => $progres,
                        'reg_catatan' => $request->reg_catatan,
                        'reg_kode_ont' => '',
                        'reg_mac' => '',
                        'reg_sn' => '',
                        'reg_mrek' => '',
                    ]);
                    $notifikasi = [
                        'pesan' => 'Berhasil melakukan pemutusan pelanggan',
                        'alert' => 'success',
                    ];
                    // echo '<p style="font-size: 200px" >CILUK........BA</p>';
                    return redirect()->route('admin.biller.index')->with($notifikasi);
                } else {
                    $notifikasi = [
                        'pesan' => 'Gagal melakukan pemutusan pelanggan. Router disconnected',
                        'alert' => 'error',
                    ];
                    return redirect()->route('admin.biller.index')->with($notifikasi);
                }
            } else {
                $notifikasi = [
                    'pesan' => 'Gagal melakukan pemutusan pelanggan. Mac Address tidak sesuai dengan yang digunakan',
                    'alert' => 'error',
                ];
                return redirect()->route('admin.biller.index')->with($notifikasi);
            }
        } else { #JIKA MAC TIDAK ADA PADA TABLE REGISTRASI
            $ip =   $query->router_ip . ':' . $query->router_port_api;
            $user = $query->router_username;
            $pass = $query->router_password;
            $API = new RouterosAPI();
            $API->debug = false;


            // dd($query);
            if ($API->connect($ip, $user, $pass)) {
                $cek_status = $API->comm('/ppp/active/print', [
                    '?name' => $query->reg_username,
                ]);
                if ($cek_status) {
                    $API->comm('/ppp/active/remove', [
                        '.id' => $cek_status[0]['.id'],
                    ]);
                }
                $cari_pel = $API->comm('/ppp/secret/print', [
                    '?name' => $query->reg_username,
                ]);
                if ($cari_pel) {
                    $API->comm('/ppp/secret/remove', [
                        '.id' =>  $cari_pel['0']['.id']
                    ]);
                }

                $data = Invoice::where('inv_idpel', $idpel)->where('inv_status', '!=', 'PAID')->first();
                if ($data) {
                    $data->delete();
                    SubInvoice::where('subinvoice_id', $data->inv_id)->delete();
                }

                // CEK BARANG 
                $cek_subbarang = SubBarang::where('subbarang_mac', $request->reg_mac)->first();
                $cek_suplier = supplier::where('supplier_nama', 'ONT')->first();
                if ($cek_subbarang) {
                    // JIKA ONT ADA 
                    $update_barang['subbarang_status'] = '0';
                    $update_barang['subbarang_keluar'] = '0';
                    $update_barang['subbarang_stok'] = '1';
                    $update_barang['subbarang_mac'] = $request->reg_mac;
                    $update_barang['subbarang_keterangan'] = $keterangan;
                    $update_barang['subbarang_admin'] = $nama_admin;
                    SubBarang::where('subbarang_mac', $request->reg_mac)->update($update_barang);

                    SubBarang::create(
                        [
                            "id_subbarang" => mt_rand(100000, 999999),
                            "subbarang_idbarang" => '811170',
                            "subbarang_nama" => $keterangan,
                            "subbarang_keterangan" => $keterangan,
                            "subbarang_ktg" => 'ADAPTOR',
                            "subbarang_qty" => 1,
                            "subbarang_keluar" => '0',
                            "subbarang_stok" => 1,
                            "subbarang_harga" => 0,
                            "subbarang_tgl_masuk" => $tgl,
                            "subbarang_status" => '0',
                            "subbarang_admin" => $nama_admin,
                        ]
                    );
                } else {
                    // JIKA ONT TIDAK ADA
                    // BUAT DAFTAR ONT BARU
                    // BUAT DENGAN NAMA SUPLIER ONT TARIKAN
                    // CEK SUDAH ADA ATAU BELUM NAMA SUPLIER ONT TARIKAN



                    if ($cek_suplier) {
                        // JIKA SUPLIER ADA MAKA EKSEKUSI BUAT DAFTAR BARANG
                        $data['supplier'] = $cek_suplier->id_supplier;
                    } else {
                        // JIKA SUPLIER TIDAK ADA MAKA EKSEKUSI BUAT SUPLIER TERLEBIH DAHULU
                        $count = supplier::count();
                        if ($count == 0) {
                            $id_supplier = 1;
                        } else {
                            $id_supplier = $count + 1;
                        }
                        $id_supplier = '1' . sprintf("%03d", $id_supplier);

                        supplier::create([
                            'id_supplier' => $id_supplier,
                            'supplier_nama' => 'ONT',
                            'supplier_alamat' => 'Jl. Tampomas Perum. Alam Tirta Lestari Blok D5 No 06',
                            'supplier_tlp' => '081386987015',
                        ]);
                        $data['supplier'] = $id_supplier;
                    }

                    $cek_barang = Barang::where('id_supplier', $data['supplier'])->first();
                    if ($cek_barang) {
                        $id['subbarang_idbarang'] = $cek_barang->id_barang;
                    } else {

                        $id['subbarang_idbarang'] = mt_rand(100000, 999999);
                        Barang::create([
                            'id_barang' => $id['subbarang_idbarang'],
                            'id_trx' => '-',
                            'id_supplier' => $data['supplier'],
                            'barang_tgl_beli' => '1',
                        ]);
                    }

                    SubBarang::create(
                        [
                            "id_subbarang" => mt_rand(100000, 999999),
                            "subbarang_idbarang" => $id['subbarang_idbarang'],
                            "subbarang_nama" => $keterangan,
                            "subbarang_keterangan" => $keterangan,
                            "subbarang_ktg" => 'ONT',
                            "subbarang_qty" => 1,
                            "subbarang_keluar" => '0',
                            "subbarang_stok" => 1,
                            "subbarang_harga" => 0,
                            "subbarang_tgl_masuk" => $tgl,
                            "subbarang_status" => '0',
                            "subbarang_mac" => $request->reg_mac,
                            "subbarang_admin" => $nama_admin,
                        ]
                    );

                    SubBarang::create(
                        [
                            "id_subbarang" => mt_rand(100000, 999999),
                            "subbarang_idbarang" => '811170',
                            "subbarang_nama" => $keterangan,
                            "subbarang_keterangan" => $keterangan,
                            "subbarang_ktg" => 'ADAPTOR',
                            "subbarang_qty" => 1,
                            "subbarang_keluar" => '0',
                            "subbarang_stok" => 1,
                            "subbarang_harga" => 0,
                            "subbarang_tgl_masuk" => $tgl,
                            "subbarang_status" => '0',
                            "subbarang_admin" => $nama_admin,
                        ]
                    );
                }

                Registrasi::where('reg_idpel', $idpel)->update([
                    'reg_progres' => $progres,
                    'reg_catatan' => $request->reg_catatan,
                    'reg_kode_ont' => '',
                    'reg_mac' => '',
                    'reg_sn' => '',
                    'reg_mrek' => '',
                ]);
                $notifikasi = [
                    'pesan' => 'Berhasil melakukan pemutusan pelanggan',
                    'alert' => 'success',
                ];
                // echo '<p style="font-size: 200px" >CILUK........BA</p>';
                return redirect()->route('admin.biller.index')->with($notifikasi);
            } else {
                $notifikasi = [
                    'pesan' => 'Gagal melakukan pemutusan pelanggan. Router disconnected',
                    'alert' => 'error',
                ];
                return redirect()->route('admin.biller.index')->with($notifikasi);
            }
        }
    }
}
