<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAkun;
use App\Models\Transaksi\Laporan;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data['dat'] = "Laporan";
        $data['admin'] = User::get();
        $data['akun'] = SettingAkun::get();
        $data['admin_q'] = $request->query('admin_q');
        $data['q'] = $request->query('q');
        $data['ak'] = $request->query('ak');
        $query = Laporan::orderBy('laporans.lap_tgl', 'DESC')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
            // ->where('laporans.lap_admin', '=', $admin_user)
            ->where(function ($query) use ($data) {
                $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
            });

        if ($data['ak'])
            $query->where('setting_akuns.akun_id', '=', $data['ak']);

        $data['laporan'] = $query->get();

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
}
