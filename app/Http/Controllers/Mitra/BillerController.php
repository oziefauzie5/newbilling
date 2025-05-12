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
    public function biller_mutasi_sales()
    {
        $admin_user = Auth::user()->id;
        $data['tittle'] = 'MITRA';
        $query =  DB::table('mutasi_sales')
            ->orderBy('mutasi_sales.created_at', 'DESC')
            ->where('mutasi_sales.smt_user_id', '=', $admin_user);
        $data['mutasi_sales'] = $query->get();

        return view('biller/mutasi_sales', $data);
    }
    public function mutasi_sales_pdf(Request $request)
    {
        // dd('test');
        // $month = date('m',strtotime(Carbon::now()));
        // $year = date('Y',strtotime(Carbon::now()));
        $month = date('Y-m-01', strtotime(Carbon::now()));
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;

        $data['start_date'] =  $request->start_date;
        $data['end_date'] =  $request->end_date;

        $query =  DB::table('mutasi_sales')
            ->orderBy('mutasi_sales.smt_tgl_transaksi', 'ASC')
            ->where('mutasi_sales.smt_user_id', '=', $data['admin_user'])
            ->whereDate('mutasi_sales.created_at', '>=', $data['start_date'])
            ->whereDate('mutasi_sales.created_at', '<=', $data['end_date']);
        $data['mutasi_sales'] = $query->get();

        $query_pel =  InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->orderBy('registrasis.reg_tgl_pasang', 'ASC')
            ->where('users.id', '=', $data['admin_user']);
        $data['data_pelannggan'] = $query_pel->get();

        $querycount = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('users.id', '=', $data['admin_user']);
        $data['total_pel'] = $querycount->count();
        $data['count_pelfree'] = $querycount->where('registrasis.reg_jenis_tagihan', '=', 'Free')->count();

        $querycount2 = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_progres', '>', 5)
            ->where('users.id', '=', $data['admin_user']);
        $data['count_putus'] = $querycount2->count();

        $querycount3 = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('users.id', '=', $data['admin_user'])
            ->whereDate('reg_tgl_pasang', '>', $month)
            ->where('reg_jenis_tagihan', '!=', 'FREE');
        $data['count_pel_baru'] = $querycount3->count();

        $data['pel_aktif'] = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_progres', '<=', 5)
            ->where('registrasis.reg_jenis_tagihan', '!=', 'FREE')
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('users.id', '=', $data['admin_user'])
            ->count();

        #COUNT PELANGGAN LUNAS
        $query_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', 'PAID')
            ->where('inv_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $data['admin_user']);
        $data['pelanggan_lunas'] = $query_lunas->count();


        $data['saldo'] = (new globalController)->total_mutasi_sales($data['admin_user']);
        return view('biller/mutasi_pdf', $data);
        $pdf = App::make('dompdf.wrapper');
        $html = view('biller/mutasi_pdf', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download('Mutasi-Sales.pdf');
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
        // $id_cust = '12264';
        // dd($id_cust);
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

    public function biller_pelanggan(Request $request)
    {
        $date = Carbon::now();
        $month = date('Y-m-01', strtotime($date));
        $user = (new GlobalController)->user_admin();
        $user_id = $user['user_id'];
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $data['q'] = $request->query('q');
        $data['putus'] = $request->query('putus');
        $data['fee'] = $request->query('fee');





        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '>=', 3)
            ->orderBy('tgl', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_jenis_tagihan', 'like', '%' . $data['q'] . '%');
            });
        // dd($data['putus']);
        if ($data['putus'])
            $query->where('registrasis.reg_progres', '>', 5);
        if ($data['fee'])
            $query->whereDate('reg_tgl_pasang', '<', $month);
        $query->whereIn('registrasis.reg_progres', [3, 4, 5]);
        $query->where('reg_jenis_tagihan', '!=', 'FREE');

        $data['data_pelanggan'] = $query->get();



        // dd($month);
        #COUNT PELANGGANG AKTIF
        $query_aktif = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '<=', 5)
            ->whereDate('reg_tgl_pasang', '<', $month);
        // $data['pelanggan_aktif'] = $query_aktif->where('reg_jenis_tagihan', '!=', 'FREE')->get();
        // foreach ($data['pelanggan_aktif'] as $key ) {
        //     # code...
        //     echo '<table><tr><th>'.$key->reg_nolayanan.'</th><th>'.$key->input_nama.'</th></tr></table>';
        // }

        // dd('test');
        $data['pelanggan_aktif'] = $query_aktif->where('reg_jenis_tagihan', '!=', 'FREE')->count();

        #COUNT PELANGGAN BULAN INI
        $query_bulan_ini = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '<=', 5)
            ->whereDate('reg_tgl_pasang', '>=', $month);
        $data['pelanggan_bulan_ini'] = $query_bulan_ini->where('reg_jenis_tagihan', '!=', 'FREE')->count();

        #COUNT PELANGGAN PUTUS
        $query_putus = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '>', 5);
        $data['pelanggan_putus'] = $query_putus->count();

        #COUNT PELANGGAN FREE
        $query_free = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '>=', 3);
        $data['total_pelanggan'] = $query_free->count();
        $data['pelanggan_free'] = $query_free->where('reg_jenis_tagihan', '=', 'FREE')->count();

        #COUNT PELANGGAN LUNAS
        $query_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', 'PAID')
            ->where('inv_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $user_id);
        $data['pelanggan_lunas'] = $query_lunas->count();


        #COUNT PELANGGAN BELUM LUNAS
        $query_belum_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            // ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $user_id);

        // $data['pelanggan_belum_lunas'] = $query_belum_lunas->get();
        // foreach ($data['pelanggan_belum_lunas'] as $key ) {
        //         # code...
        //         echo '<table><tr><th>'.$key->reg_nolayanan.'</th><th>'.$key->input_nama.'</th></tr></table>';
        //     }

        //     dd('test');
        $data['pelanggan_belum_lunas'] = $query_belum_lunas->count();
        $data['komisi'] = (new globalController)->total_mutasi_sales($user_id);

        return view('biller/data_pelanggan_sales', $data);
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
        $sum_trx = Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $tgl_bayar)->sum('trx_debet');
        $count_trx = Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $tgl_bayar)->sum('trx_qty');
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
            $datas['inv_tgl_bayar'] = $if_tgl_bayar;
            $datas['inv_status'] = 'PAID';


            $data_lap['lap_id'] = time();
            $data_lap['lap_tgl'] = $if_tgl_bayar;
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

            $pesan_group['pesan_id_site'] = '1';
            $pesan_group['layanan'] = 'CS';
            $pesan_group['ket'] = 'payment';
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

            if ($data_pelanggan->reg_layanan == 'PPP') {



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
                            $data_trx['trx_kategori'] = 'Pendapatan';
                            $data_trx['trx_jenis'] = 'Invoice';
                            $data_trx['trx_admin'] = 'System';
                            $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                            $data_trx['trx_qty'] = 1;
                            $data_trx['trx_debet'] = $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->create($data_trx);
                        } else {
                            $i = '1';
                            $data_trx['trx_qty'] = $count_trx + $i;
                            $data_trx['trx_debet'] = $sum_trx + $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $if_tgl_bayar)->update($data_trx);
                        }

                        #CEK BULAN PEMASANGAN
                        $bulan_pasang = date('Y-m', strtotime($data_pelanggan->reg_tgl_pasang));
                        $bulan_bayar = date('Y-m', strtotime($if_tgl_bayar));
                        if ($bulan_pasang != $bulan_bayar) {
                            if ($data_pelanggan->reg_fee > 0) {
                                $data_biaya = SettingBiaya::first();
                                $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                                $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                                $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                                $mutasi_sales['smt_admin'] = $admin_user;
                                $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
                                $mutasi_sales['smt_tgl_transaksi'] = $if_tgl_bayar;
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
                            $data_trx['trx_kategori'] = 'Pendapatan';
                            $data_trx['trx_jenis'] = 'Invoice';
                            $data_trx['trx_admin'] = 'System';
                            $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                            $data_trx['trx_qty'] = 1;
                            $data_trx['trx_debet'] = $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->create($data_trx);
                        } else {
                            $i = '1';
                            $data_trx['trx_qty'] = $count_trx + $i;
                            $data_trx['trx_debet'] = $sum_trx + $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $if_tgl_bayar)->update($data_trx);
                        }
                        if ($data_pelanggan->reg_fee > 0) {
                            $data_biaya = SettingBiaya::first();
                            $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                            $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                            $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                            $mutasi_sales['smt_admin'] = $admin_user;
                            $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
                            $mutasi_sales['smt_tgl_transaksi'] = $if_tgl_bayar;
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
                #JIKA PAYMENT UNTUK HOTSPOT
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'profile' => $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                            'disabled' => 'no',
                        ]);
                        if ($count_trx == 0) {
                            $data_trx['trx_kategori'] = 'Pendapatan';
                            $data_trx['trx_jenis'] = 'Invoice';
                            $data_trx['trx_admin'] = 'System';
                            $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                            $data_trx['trx_qty'] = 1;
                            $data_trx['trx_debet'] = $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->create($data_trx);
                        } else {
                            $i = '1';
                            $data_trx['trx_qty'] = $count_trx + $i;
                            $data_trx['trx_debet'] = $sum_trx + $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $if_tgl_bayar)->update($data_trx);
                        }

                        if ($data_pelanggan->reg_fee > 0) {
                            $data_biaya = SettingBiaya::first();
                            $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                            $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                            $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                            $mutasi_sales['smt_admin'] = $admin_user;
                            $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
                            $mutasi_sales['smt_tgl_transaksi'] = $if_tgl_bayar;
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
                        $cek_status = $API->comm('/ip/hotspot/active/print', [
                            '?user' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ip/hotspot/active/remove', [
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
                        $API->comm('/ip/hotspot/user/add', [
                            'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                            'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                            'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                            'disabled' => 'no',
                        ]);
                        if ($count_trx == 0) {
                            $data_trx['trx_kategori'] = 'Pendapatan';
                            $data_trx['trx_jenis'] = 'Invoice';
                            $data_trx['trx_admin'] = 'System';
                            $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                            $data_trx['trx_qty'] = 1;
                            $data_trx['trx_debet'] = $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->create($data_trx);
                        } else {
                            $i = '1';
                            $data_trx['trx_qty'] = $count_trx + $i;
                            $data_trx['trx_debet'] = $sum_trx + $data_pelanggan->inv_total;
                            Transaksi::where('trx_jenis', 'Invoice')->whereDate('created_at', $if_tgl_bayar)->update($data_trx);
                        }
                        if ($data_pelanggan->reg_fee > 0) {
                            $data_biaya = SettingBiaya::first();
                            $saldo = (new globalController)->total_mutasi_sales($data_pelanggan->reg_idpel);
                            $total = $saldo + $data_biaya->biaya_sales_continue; #SALDO MUTASI = DEBET - KREDIT

                            $mutasi_sales['smt_user_id'] = $data_pelanggan->input_sales;
                            $mutasi_sales['smt_admin'] = $admin_user;
                            $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
                            $mutasi_sales['smt_tgl_transaksi'] = $if_tgl_bayar;
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

    public function biller_putus_berlanggan(Request $request, $idpel) {}
}
