<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAkun;
use App\Models\Model_Has_Role;
use App\Models\Transaksi\DataLaporan;
use App\Models\Transaksi\Laporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;
        $ids = [1, 2, 5, 10, 13, 14];
        $data['dat'] = "Laporan";
        $role = Model_Has_Role::where('model_id', $data['admin_user'])->first();



        $data['akun'] = SettingAkun::get();
        $data['adm'] = $request->query('adm');


        $data['q'] = $request->query('q');
        $data['ak'] = $request->query('ak');
        if ($role->role_id == 1) {
            $data['admin'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->whereIn('model_has_roles.role_id', $ids)->get();
            $query = Laporan::orderBy('laporans.lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
                // ->where('laporans.lap_admin', '=', $admin_user)
                ->where(function ($query) use ($data) {
                    $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        } else {
            $data['admin'] = User::where('id', $data['admin_user'])->get();
            // dd($data['admin']);
            $query = Laporan::orderBy('laporans.lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
                ->where('laporans.lap_admin', '=', $data['admin_user'])
                ->where(function ($query) use ($data) {
                    $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        }



        $data['buat_laporan'] = $query->where('lap_status', 0)->sum('laporans.lap_kredit');
        $data['sum_tunai'] = $query->where('lap_status', 0)->sum('laporans.lap_kredit');
        if ($data['ak'])
            $query->where('setting_akuns.akun_nama', '=', $data['ak']);
        if ($data['adm'])
            $query->where('users.name', '=', $data['adm']);

        // $data['sum_tunai'] = 0;
        $data['laporan'] = $query->get();
        $data['pendapatan'] = $query->where('lap_status', 0)->sum('laporans.lap_kredit');
        $data['refund'] = $query->where('lap_status', 0)->sum('laporans.lap_debet');
        $data['biaya_adm'] = $query->where('lap_status', 0)->sum('laporans.lap_adm');
        $data['count_trx'] = $query->where('lap_status', 0)->count();
        // dd($data['sum']);
        return view('Transaksi/laporan_harian', $data);
    }

    public function lap_delete($id)
    {

        // dd($id);
        $data = Laporan::where('lap_id', $id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data Laporan',
            'alert' => 'success',
        ];
        return redirect()->route('admin.inv.laporan')->with($notifikasi);
    }

    public function buat_laporan(Request $request, $id)
    {
        // dd($id);
        // dd(Laporan::where('lap_status', '0')->where('lap_admin', $id)->first());
        $cek_id = DataLaporan::where('data_lap_id', $request->lap_id)->first();
        if ($cek_id) {
            $notifikasi = [
                'pesan' => 'Maaf, Anda gagal membuat laporan. silahkan ulangi kembali yah',
                'alert' => 'error',
            ];
            return redirect()->route('admin.inv.laporan')->with($notifikasi);
        } else {
            $tgl = date('Y-m-d', strtotime(Carbon::now()));
            $data['data_lap_id'] = $request->lap_id;
            $data['data_lap_tgl'] = $tgl;
            $data['data_lap_pendapatan'] = $request->total;
            $data['data_lap_tunai'] = $request->tunai;
            $data['data_lap_adm'] = $request->adm;
            $data['data_lap_admin'] = $id;
            $data['data_lap_keterangan'] = 'Laporan Harian ' . $request->user_admin;
            $data['data_lap_status'] = 0;
            $update_data['lap_status'] = 1;
            $update_data['lap_id'] = $request->lap_id;
            DataLaporan::create($data);
            Laporan::where('lap_status', '0')->where('lap_admin', $id)->update($update_data);
            $notifikasi = [
                'pesan' => 'Terimakasih. Laporan anda berhasil dibuat',
                'alert' => 'success',
            ];
            return redirect()->route('admin.inv.laporan')->with($notifikasi);
        };
    }
    public function data_laporan(Request $request)
    {

        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;
        $ids = [1, 2, 5, 10, 13, 14];
        $data['dat'] = "Laporan";
        $role = Model_Has_Role::where('model_id', $data['admin_user'])->first();

        $data['akun'] = SettingAkun::get();
        $data['adm'] = $request->query('adm');


        $data['q'] = $request->query('q');
        $data['ak'] = $request->query('ak');
        if ($role->role_id == 1) {
            $data['admin'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->whereIn('model_has_roles.role_id', $ids)->get();
            $query = DataLaporan::orderBy('data_laporans.data_lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'data_laporans.data_lap_admin')
                ->where(function ($query) use ($data) {
                    $query->where('data_lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        } else {
            $data['admin'] = User::where('id', $data['admin_user'])->get();
            // dd($data['admin']);
            $query = DataLaporan::orderBy('data_laporans.data_lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'data_laporans.data_lap_admin')
                ->where('data_laporans.data_lap_admin', '=', $data['admin_user'])
                ->where(function ($query) use ($data) {
                    $query->where('data_lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        }

        if ($data['ak'])
            $query->where('setting_akuns.akun_nama', '=', $data['ak']);
        if ($data['adm'])
            $query->where('users.name', '=', $data['adm']);

        $data['laporan'] = $query->get();

        return view('Transaksi/data_laporan', $data);
    }
}
