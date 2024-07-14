<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController as GlobalGlobalController;
use App\Http\Controllers\globalController;
use App\Http\Controllers\WhatsappController;
use App\Models\Applikasi\SettingAkun as ApplikasiSettingAkun;
use App\Models\Global\ConvertNoHp;
use App\Models\Laporan\laporanharian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\User;
use App\Models\Model_Has_Role;
use App\Models\Setting\SettingAkun;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;


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
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        $d = date_create($request->tgl_gabung);
        $th = date_format($d, "Y");
        $bl = date_format($d, "m");
        $tg = date_format($d, "d");
        $rand = rand(100, 9999);
        $id_mitra = $th . $bl  . $rand . $tg;
        $data['id'] = $id_mitra;
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        $data['password'] = Hash::make($request->password);

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
    public function data($id)
    {

        $saldo = (new GlobalGlobalController)->total_mutasi($id);


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
                ->orderBy('mutasis.id', 'DESC')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
                ->where('mutasis.mt_mts_id', '=', $id)
                ->get(),
            'akun' => (new ApplikasiSettingAkun())->SettingAkun(),
            'saldo' => $saldo,
        );
        return view('mitra/data', $data);
    }

    public function topup(Request $request, $id)
    {

        $invoice = (new GlobalGlobalController)->no_invoice_mitra();
        $count = Mutasi::count();
        if ($count == 0) {
            $count_invoice = 1;
        } else {
            $count_invoice = $count + 1;
        }
        // dd($count_invoice);
        $invoice = sprintf("%08d", $count_invoice);
        #CEK SALDO MUTASI BILLER
        $saldo = (new GlobalGlobalController)->total_mutasi($id); #SALDO MUTASI = DEBET - KREDIT
        $total = $saldo + $request->nominal;

        #mennampilkan data user sesuai hak akses
        $user = (new GlobalGlobalController)->data_user($id);

        #Admin yang sedang aktif (Membuat topup)
        $admin_user = Auth::user()->id;

        #Cek saldo terakhir pada laporan harian admin
        // $saldo_laporan_harian = (new GlobalGlobalController)->laporan_harian($admin_user);
        // $total_laporan_harian = $saldo_laporan_harian + $request->nominal; #SALDO LAPORAN HARIAN = DEBET - KREDIT

        // dd($total_laporan_harian);
        // dd($total_laporan_harian);

        $data['mt_mts_id'] = $id;
        $data['mt_admin'] = $admin_user;
        $data['mt_kategori'] = 'TOPUP';
        $data['mt_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE ' . $invoice;
        $data['mt_kredit'] = $request->nominal;
        $data['mt_saldo'] = $total;
        $data['mt_cabar'] = $request->cabar;
        Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI

        // $lh['lh_id'] = $invoice;
        // $lh['lh_admin'] = $admin_user;
        // $lh['lh_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE ' . $invoice;
        // $lh['lh_qty'] = '1';
        // $lh['lh_kredit'] = $request->nominal;
        // $lh['lh_saldo'] = $total_laporan_harian;
        // $lh['lh_akun'] = $request->cabar;
        // $lh['lh_status'] = '0';
        // $lh['lh_kategori'] = 'TOPUP';
        // laporanharian::create($lh); #INSERT LAPORAN TOPUP KE TABLE LAPORAN HARIAN


        $notifikasi = array(
            'pesan' => 'TOPUP Saldo berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }
    public function debet_saldo(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $invoice = (new globalController)->no_invoice_mitra();
        $saldo = (new globalController)->total_mutasi($id);
        $user = (new globalController)->data_user($id);
        $total = $saldo - $request->nominal_debet;
        $saldo_laporan_harian = (new globalController)->laporan_harian($admin_user);
        $total_laporan_harian = $saldo_laporan_harian - $request->nominal_debet;

        // dd($total_laporan_harian);

        if ($total <= 0) {
            $notifikasi = array(
                'pesan' => 'Saldo tidak mencukupi',
                'alert' => 'error',
            );
        } else {
            $invoice = (new globalController)->no_invoice_mitra();

            $data['mt_mts_id'] = $id;
            $data['mt_admin'] = $admin_user;
            $data['mt_kategori'] = $request->kategori;
            $data['mt_deskripsi'] = 'Invoice ' . $invoice;
            $data['mt_kredit'] = '0';
            $data['mt_debet'] = $request->nominal_debet;
            $data['mt_saldo'] = $total;
            $data['mt_cabar'] = $request->cabar;
            Mutasi::create($data);

            $lh['lh_id'] = $invoice;
            $lh['lh_admin'] = $admin_user;
            $lh['lh_deskripsi'] = $request->kategori . ' ' . $user->nama_user . ' INVOICE ' . $invoice;
            $lh['lh_qty'] = '1';
            $lh['lh_kredit'] = '0';
            $lh['lh_debet'] = $request->nominal_debet;
            $lh['lh_saldo'] = $total_laporan_harian;
            $lh['lh_akun'] = $request->cabar;
            $lh['lh_status'] = '0';
            $lh['lh_kategori'] = $request->kategori;
            laporanharian::create($lh);
            $notifikasi = array(
                'pesan' => $request->kategori . ' Saldo berhasil',
                'alert' => 'success',
            );
        }
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }
}
