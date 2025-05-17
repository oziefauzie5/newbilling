<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
// use App\Models\Applikasi\SettingAkun;
use App\Models\Global\ConvertNoHp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\User;
use App\Models\Model_Has_Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\Transaksi\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Storage;


class MitraController extends Controller
{
    public function index()
    {
        $data = array(
            'tittle' => 'MITRA',
            'datauser' => DB::table('users')
                ->select('users.name AS nama', 'users.alamat_lengkap', 'users.id', 'users.hp', 'users.username', 'roles.name', 'mitra_settings.mts_limit_minus', 'mitra_settings.mts_kode_unik', 'mitra_settings.mts_komisi')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
                ->get(),
        );
        return view('mitra/index', $data);
    }
    public function create()
    {
        $data = array(
            'tittle' => 'TAMBAH MITRA',

        );
        // dd($data);
        return view('mitra/create', $data);
    }

    public function addmitra(Request $request)
    {
        $validator = FacadesValidator::make(
            $request->all(),
            [

                'username' => 'unique:users',
                'hp' => 'required|unique:users',
            ],
            [
                'username.unique' => 'Username sudah digunakan.',
                'hp.unique' => 'Nomor Hp sudah terdaftar.',
            ]
        );
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $level = $get[1];


        $data_level['id'] = $level_id;
        $data_level['name'] = $level;
        $data_level['guard_name'] = 'web';

        Role::updateorcreate($data_level);
        Permission::updateorcreate($data_level);
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        // dd($request->tgl_gabung);
        $d = date_create($request->tgl_gabung);
        $th = date_format($d, "y");
        $bl = date_format($d, "m");
        $tg = date_format($d, "d");
        $rand = rand(100, 9999);
        $id_mitra = $th . $bl  . $rand . $tg;
        // dd($id_mitra);
        $data['id'] = $id_mitra;
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        $data['password'] = Hash::make($request->password);
        $data['status_user'] = $request->status_user;

        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id_mitra;

        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;

        // $idi = time();
        // $mitra_setting['mts_id'] = $idi;
        $mitra_setting['mts_user_id'] = $id_mitra;
        $mitra_setting['mts_limit_minus'] = $request->limit_minus;
        $mitra_setting['mts_kode_unik'] = $request->kode_unik;
        $mitra_setting['mts_komisi'] = $request->mts_komisi;

        MitraSetting::create($mitra_setting);

        #DISABLE SEMENTAR UNTUK TES WA
        if (!RoleHasPermission::where('permission_id', $level_id)
            ->where('role_id', $level_id)
            ->get()
            ->isEmpty()) {
            User::create($data);
            Model_Has_Role::create($datarole);
        } else {
            User::create($data);
            Model_Has_Role::create($datarole);
            RoleHasPermission::create($datarolepermission);
        }


        $notifikasi = array(
            'pesan' => 'User berhasil ditambahkan',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.index')->with($notifikasi);
    }
    public function store_edit(Request $request, $id)
    {

        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $level = $get[1];

        $data_level['id'] = $level_id;
        $data_level['name'] = $level;
        $data_level['guard_name'] = 'web';

        Role::updateorcreate($data_level);
        Permission::updateorcreate($data_level);

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $data['email'] = $request->email;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        if (User::whereId($id)->first('username')->username != $request->username) {
            $data['username'] = $request->username;
        }



        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id;

        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;

        $mitra_setting['mts_limit_minus'] = $request->limit_minus;
        $mitra_setting['mts_kode_unik'] = $request->kode_unik;
        $mitra_setting['mts_komisi'] = $request->mts_komisi;

        // MitraSetting::where('mts_user_id', $id)->update($mitra_setting);

        #DISABLE SEMENTAR UNTUK TES WA
        if (!RoleHasPermission::where('permission_id', $level_id)
            ->where('role_id', $level_id)
            ->get()
            ->isEmpty()) {
            User::whereId($id)->update($data);
            Model_Has_Role::where('model_id', $id)->update($datarole);
            // dd(1);
        } else {
            User::whereId($id)->update($data);
            Model_Has_Role::where('model_id', $id)->update($datarole);
            RoleHasPermission::updateorcreate($datarolepermission);
        }


        $notifikasi = array(
            'pesan' => 'User berhasil ditambahkan',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.index')->with($notifikasi);
    }
    public function edit($id)
    {
        $data['data_mitra'] = DB::table('users')
            ->select('users.name AS nama', 'users.*', 'users.created_at as tgl', 'roles.name as role_name', 'roles.id as role_id', 'mitra_settings.*')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->first();

        return view('mitra/edit', $data);
    }
    public function data($id)
    {

        $saldo = (new GlobalController)->total_mutasi($id);
        $saldo_sales = (new GlobalController)->total_mutasi_sales($id);


        $data = array(
            'tittle' => 'MITRA',
            'datauser' => DB::table('users')
                ->select('users.name AS nama', 'users.alamat_lengkap', 'users.id', 'users.hp', 'users.username', 'roles.name', 'mitra_settings.mts_limit_minus', 'mitra_settings.mts_kode_unik', 'mitra_settings.mts_komisi')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
                ->where('users.id', '=', $id)
                ->first(),
            'mutasi' =>  DB::table('mutasis')
                ->select('mutasis.*', 'mutasis.created_at as tgl_trx', 'mitra_settings.*')
                ->orderBy('mutasis.id', 'DESC')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
                ->where('mutasis.mt_mts_id', '=', $id)
                ->get(),
            'mutasi_saldo' =>  DB::table('mutasi_sales')
                ->select('mutasi_sales.*', 'mutasi_sales.created_at as tgl_trx', 'mitra_settings.*')
                ->orderBy('mutasi_sales.id', 'ASC')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasi_sales.smt_user_id')
                ->where('mutasi_sales.smt_user_id', '=', $id)
                ->get(),
            'akun' => (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'LAPORAN')->get(),
            'saldo' => $saldo,
            'saldo_sales' => $saldo_sales,
        );
        return view('mitra/data', $data);
    }

    public function topup(Request $request, $id)
    {
        // dd($photo = $request->file('file'));
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        // $akun = (new SettingAkun())->SettingAkun()->where('akun_id', $request->cabar)->first();
        $akun = (new GlobalController)->setting_akun()->where('akun_id', $request->cabar)->first();
        // dd($akun);
        $invoice = (new GlobalController)->no_invoice_mitra();
        $count = Mutasi::count();
        if ($count == 0) {
            $count_invoice = 1;
        } else {
            $count_invoice = $count + 1;
        }
        // dd($count_invoice);
        $invoice = sprintf("%08d", $count_invoice);
        #CEK SALDO MUTASI BILLER
        $saldo = (new GlobalController)->total_mutasi($id); #SALDO MUTASI = DEBET - KREDIT
        $total = $saldo + $request->nominal;

        #mennampilkan data user sesuai hak akses
        $user = (new GlobalController)->data_user($id);

        #Admin yang sedang aktif (Membuat topup)
        $admin_user = Auth::user()->id;

        $data['mt_mts_id'] = $id;
        $data['mt_admin'] = $admin_user;
        $data['mt_kategori'] = 'TOPUP';
        $data['mt_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
        $data['mt_kredit'] = $request->nominal;
        $data['mt_saldo'] = $total;
        $data['mt_cabar'] = $request->cabar;



        Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI

        $data_lap['lap_id'] = 0;
        $data_lap['lap_tgl'] = $tgl_bayar;
        $data_lap['lap_inv'] = $invoice;
        $data_lap['lap_admin'] = $admin_user;
        $data_lap['lap_cabar'] = $akun->akun_nama;
        $data_lap['lap_debet'] = 0;
        $data_lap['lap_kredit'] = $request->nominal;
        $data_lap['lap_adm'] = 0;
        $data_lap['lap_jumlah_bayar'] = $request->nominal;
        $data_lap['lap_keterangan'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
        $data_lap['lap_akun'] = $request->cabar;
        $data_lap['lap_idpel'] = 0;
        $data_lap['lap_jenis_inv'] = "TOPUP";
        $data_lap['lap_status'] = 0;
        $photo = $request->file('file');
        $filename = $user->nama_user . date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
        $path = 'bukti-transfer/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $data['lap_img'] = $filename;
        Laporan::create($data_lap);



        $notifikasi = array(
            'pesan' => 'TOPUP Saldo berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }
    public function debet_saldo(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $akun = (new GlobalController)->SettingAkun()->where('akun_id', $request->cabar)->first();

        $invoice = (new GlobalController)->no_invoice_mitra();
        $saldo = (new GlobalController)->total_mutasi($id);
        $user = (new GlobalController)->data_user($id);
        $total = $saldo - $request->nominal_debet;
        // $saldo_laporan_harian = (new GlobalController)->laporan_harian($admin_user);
        // $total_laporan_harian = $saldo_laporan_harian - $request->nominal_debet;

        // dd($total_laporan_harian);

        if ($total <= 0) {
            $notifikasi = array(
                'pesan' => 'Saldo tidak mencukupi',
                'alert' => 'error',
            );
        } else {
            $invoice = (new GlobalController)->no_invoice_mitra();

            $data['mt_mts_id'] = $id;
            $data['mt_admin'] = $admin_user;
            $data['mt_kategori'] = $request->kategori;
            $data['mt_deskripsi'] = 'INVOICE-' . $invoice;
            $data['mt_kredit'] = '0';
            $data['mt_debet'] = $request->nominal_debet;
            $data['mt_saldo'] = $total;
            $data['mt_cabar'] = $request->cabar;
            Mutasi::create($data);

            // dd($data['mt_debet']);

            $data_lap['lap_id'] = 0;
            $data_lap['lap_tgl'] = $tgl_bayar;
            $data_lap['lap_inv'] = $invoice;
            $data_lap['lap_admin'] = $admin_user;
            $data_lap['lap_cabar'] = $akun->akun_nama;
            $data_lap['lap_kredit'] = 0;
            $data_lap['lap_debet'] = $data['mt_debet'];
            $data_lap['lap_adm'] = 0;
            $data_lap['lap_jumlah_bayar'] = $data['mt_debet'];
            $data_lap['lap_keterangan'] = $request->kategori . ' ' . $user->nama_user . ' INVOICE-' . $invoice;
            $data_lap['lap_akun'] = $request->cabar;
            $data_lap['lap_idpel'] = 0;
            $data_lap['lap_jenis_inv'] = "TOPUP";
            $data_lap['lap_status'] = 0;
            $photo = $request->file('file');
            $filename = $user->nama_user . date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transfer/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['lap_img'] = $filename;
            Laporan::create($data_lap);
            // dd($data_lap);

            $notifikasi = array(
                'pesan' => $request->kategori . ' Saldo berhasil',
                'alert' => 'success',
            );
        }
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }
}
