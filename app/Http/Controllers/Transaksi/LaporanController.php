<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Mitra\Mutasi;
use App\Models\Model_Has_Role;
use App\Models\Transaksi\DataLaporan;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\Jurnal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;
        $data['setting_akun'] = (new GlobalController)->setting_akun()->where('id', '=', '2')->get();
        $ids = [1, 2, 5, 10, 13, 14];
        $biller = [10, 13, 14];
        $data['dat'] = "Laporan";
        $role = Model_Has_Role::where('model_id', $data['admin_user'])->first();


        $data['now'] = date('Y-m-d', strtotime(carbon::now()));
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
                ->where(function ($query) use ($data) {
                    $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        } else {
            $data['admin'] = User::where('id', $data['admin_user'])->get();
            $query = Laporan::orderBy('laporans.lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
                ->where('laporans.lap_admin', '=', $data['admin_user'])
                ->where(function ($query) use ($data) {
                    $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        }
        $data['buat_laporan'] = $query->where('lap_status', 0)->sum('laporans.lap_kredit');

        if ($data['ak'])
            $query->where('setting_akuns.akun_nama', '=', $data['ak']);
        if ($data['adm'])
            $query->where('users.name', '=', $data['adm']);

        $data['laporan'] = $query->get();
        $data['pendapatan'] = $query->where('lap_status', 0)->sum('laporans.lap_kredit');
        $data['refund'] = $query->where('lap_status', 0)->sum('laporans.lap_debet');
        $data['biaya_adm'] = $query->where('lap_status', 0)->sum('laporans.lap_adm');
        $data['count_trx'] = $query->where('lap_status', 0)->count();


        if ($role->role_id == 1) {
            $querysum = Laporan::orderBy('laporans.lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun');
        } else {
            $querysum = Laporan::orderBy('laporans.lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
                ->where('laporans.lap_admin', '=', $data['admin_user']);
        }

        $data['sum_tunai'] = $querysum->where('lap_status', 0)->where('lap_akun', 2)->sum('laporans.lap_kredit');

        $data['users'] = (new GlobalController)->all_user()->where('model_has_roles.role_id', '=', '5')->get();
        $data['biller'] = (new GlobalController)->all_user()->whereIn('model_has_roles.role_id', $biller)->get();
        // dd($data['users']);
        return view('Transaksi/laporan_harian', $data);
    }

    public function lap_delete($id)
    {
        // dd($id);
        $data = Laporan::where('id', $id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data Laporan',
            'alert' => 'success',
        ];
        return redirect()->route('admin.inv.laporan')->with($notifikasi);
    }
    public function topup(Request $request)
    {
        // dd($id);
        $query = Laporan::select('laporans.id as laporan_id', 'laporans.*', 'setting_akuns.id as akun_id', 'setting_akuns.akun_nama', 'users.id as user_id', 'users.name')
            ->orderBy('laporans.lap_tgl', 'DESC')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->where('lap_admin', $request->user_admin);
        $data['laporan'] = $query->get();
        $data['sum'] = $query->get();
        // dd($data);
        $data['setting_akun'] = (new GlobalController)->setting_akun()->where('id', '!=', '5')->get();
        $data['admin'] = $request->user_admin;

        // dd($data);
        return view('Transaksi/topup', $data);
    }
    public function lap_topup(Request $request, $id)
    {

        $user_admin = (new GlobalController)->user_admin();
        // $aray = $request->checkboxtopup_value;

        if ($id != 10) {
            $query = Laporan::whereIn('id', $request->checkboxtopup_value);
            $data['laporan'] = $query->get();
            $data['total'] = $query->sum('lap_kredit');

            $invoice = (new GlobalController)->no_invoice_mitra();
            $count = Mutasi::count();
            if ($count == 0) {
                $count_invoice = 1;
            } else {
                $count_invoice = $count + 1;
            }
            $invoice = sprintf("%08d", $count_invoice);
            #CEK SALDO MUTASI BILLER
            $saldo = (new GlobalController)->total_mutasi($id); #SALDO MUTASI = DEBET - KREDIT
            $total = $saldo + $data['total'];

            #mennampilkan data user sesuai hak akses
            $user = (new GlobalController)->data_user($id);

            #Admin yang sedang aktif (Membuat topup)
            $admin_user = Auth::user()->id;

            $data['mt_mts_id'] = $id;
            $data['mt_admin'] = $admin_user;
            $data['mt_kategori'] = 'TOPUP';
            $data['mt_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
            $data['mt_kredit'] = $data['total'];
            $data['mt_saldo'] = $total;
            $data['mt_cabar'] = $request->cabar;

            Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI
        }

        foreach ($request->checkboxtopup_value as $d) {
            Laporan::where('lap_admin', $id)->where('id', $d)->update(
                [
                    'lap_admin' => $user_admin['user_id'],
                ]
            );
        }

        $route = URL::to('/');
        return response()->json($route);
        // return redirect()->route('admin.inv.laporan')->with($notifikasi);
    }

    public function serah_terima(Request $request, $id)
    {
        $query = Laporan::where('lap_status', '0')->where('lap_admin', $id)->get();

        $tgl = date('Y-m-d', strtotime(Carbon::now()));
        $update_data['lap_id'] = $request->lap_id;
        $update_data['lap_admin'] = $request->user_admin2;

        Laporan::where('lap_status', '0')->where('lap_admin', $id)->update($update_data);


        $notifikasi = [
            'pesan' => 'Terimakasih. Laporan anda berhasil diserah terima',
            'alert' => 'success',
        ];
        return redirect()->route('admin.inv.laporan')->with($notifikasi);
    }
    public function buat_laporan(Request $request, $id)
    {
        $nama_admin = Auth::user()->name;
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
            $data['data_lap_refund'] = $request->refund;
            $data['data_lap_trx'] = $request->count_trx;
            $data['data_lap_keterangan'] = 'Laporan Harian ' . $request->user_admin;
            $data['data_lap_status'] = 0;
            $update_data['lap_status'] = 1;
            $update_data['lap_id'] = $request->lap_id;

            // $jurnal['jurnal_id'] =  time();
            // $jurnal['jurnal_tgl'] =  $tgl;
            // $jurnal['jurnal_uraian'] =  'LAPORAN - ' . $nama_admin;
            // $jurnal['jurnal_kategori'] =  'PENDAPATAN';
            // $jurnal['jurnal_keterangan'] =  'LAPORAN';
            // $jurnal['jurnal_admin'] =  $id;
            // $jurnal['jurnal_kredit'] =  $request->total;
            // $jurnal['jurnal_metode_bayar'] =  2;
            // $jurnal['jurnal_status'] =  1;
            DataLaporan::create($data);
            Laporan::where('lap_status', '0')->where('lap_admin', $id)->update($update_data);
            // Jurnal::create($jurnal);


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
        $data['start'] =  $request->query('start');
        $data['end'] =   $request->query('end');
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
            $query = DataLaporan::orderBy('data_laporans.data_lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'data_laporans.data_lap_admin')
                ->where('data_laporans.data_lap_admin', '=', $data['admin_user'])
                ->where(function ($query) use ($data) {
                    $query->where('data_lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        }

        if ($data['start'])
            $query->whereDate('data_laporans.data_lap_tgl', '>=', $data['start']);
        if ($data['end'])
            $query->whereDate('data_laporans.data_lap_tgl', '<=', $data['end']);
        if ($data['adm'])
            $query->where('users.name', '=', $data['adm']);

        $data['laporan'] = $query->get();
        $data['pendapatan'] = $query->where('data_lap_status', 1)->sum('data_laporans.data_lap_pendapatan');
        $data['refund'] = $query->where('data_lap_status', 1)->sum('data_laporans.data_lap_refund');
        $data['biaya_adm'] = $query->where('data_lap_status', 1)->sum('data_laporans.data_lap_adm');
        $data['sum_tunai'] = $query->where('data_lap_status', 1)->sum('data_laporans.data_lap_tunai');
        $data['count_trx'] = $query->where('data_lap_status', 1)->sum('data_laporans.data_lap_trx');
        $data['count_data'] = $query->where('data_lap_status', 1)->count();

        return view('Transaksi/data_laporan', $data);
    }

    public function data_lap_delete($id)
    {

        $data = DataLaporan::where('data_lap_id', $id);
        $laporan = Laporan::where('lap_id', $id);

        if ($data) {
            if ($laporan) {
                $laporan->update([
                    'lap_id' => 0,
                    'lap_status' => 0,
                ]);
                $data->delete();
            } else {
                $notifikasi = [
                    'pesan' => 'Maaf, Anda gagal hapus laporan. Laporan tidak ditemukan',
                    'alert' => 'error',
                ];
                return redirect()->route('admin.inv.data_laporan')->with($notifikasi);
            }
        } else {
            $notifikasi = [
                'pesan' => 'Maaf, Anda gagal hapus Data laporan. Laporan tidak ditemukan',
                'alert' => 'error',
            ];
            return redirect()->route('admin.inv.data_laporan')->with($notifikasi);
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data Laporan',
            'alert' => 'success',
        ];
        return redirect()->route('admin.inv.data_laporan')->with($notifikasi);
    }

    public function laporan_print($id)
    {

        // dd($id);
        $admin_lap = Laporan::where('lap_id', $id)->first();
        // $data['admin_user'] = Auth::user()->id;
        $nama_admin = (new GlobalController)->data_user($admin_lap->lap_admin);

        // $data['admin_name'] = Auth::user()->name;

        $query = Laporan::select('data_laporans.*', 'users.*', 'setting_akuns.*', 'laporans.*', 'laporans.created_at as tgl_trx')
            ->orderBy('tgl_trx', 'DESC')
            ->join('data_laporans', 'data_laporans.data_lap_id', '=', 'laporans.lap_id')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'laporans.lap_akun')
            ->where('laporans.lap_id', '=', $id);
        $data['laporan'] = $query->get();
        $data['total'] = Laporan::where('lap_id', $id)->sum('lap_kredit') + Laporan::where('lap_id', $id)->sum('lap_adm') - Laporan::where('lap_id', $id)->sum('lap_debet');
        $data['total_tunai'] = Laporan::where('lap_id', $id)->where('lap_akun', 2)->sum('lap_kredit') + Laporan::where('lap_id', $id)->where('lap_akun', 2)->sum('lap_adm') - Laporan::where('lap_id', $id)->where('lap_akun', 2)->sum('lap_debet');
        $data['total_kas'] = Laporan::where('lap_id', $id)->sum('lap_fee_lingkungan');
        $data['total_kerjasama'] = Laporan::where('lap_id', $id)->sum('lap_fee_kerja_sama');
        $data['total_fee'] = Laporan::where('lap_id', $id)->sum('lap_fee_marketing');
        $data['total_ppn'] = Laporan::where('lap_id', $id)->sum('lap_ppn');
        $data['admin'] = $nama_admin->nama_user;
        // dd($data['total_fee']);



        $data['data_laporan'] = DataLaporan::where('data_lap_id', $id)->first();
        return view('Transaksi/print_laporan', $data);


        $pdf = App::make('dompdf.wrapper');
        $html = view('Transaksi/print_laporan', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->download('invoice.pdf');
    }

    public function store_add_transaksi(Request $request)
    {
        // dd($request->uraian);
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();

        $data_lap['lap_id'] = time();
        $data_lap['lap_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
        $data_lap['lap_admin'] = $user['user_id'];
        $data_lap['lap_cabar'] = 'TUNAI';
        $data_lap['lap_debet'] = 0;
        $data_lap['lap_kredit'] = $request->jumlah;
        $data_lap['lap_adm'] = 0;
        $data_lap['lap_jumlah_bayar'] = $request->jumlah;
        $data_lap['lap_keterangan'] = $request->jenis;
        $data_lap['lap_akun'] = $request->metode;
        $data_lap['lap_jenis_inv'] = "VOUCHER";
        $data_lap['lap_status'] = 0;
        $photo = $request->file('file');
        $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
        $path = 'bukti-transaksi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $data_lap['jurnal_img'] = $filename;

        Laporan::create($data_lap);

        $notifikasi = array(
            'pesan' => 'Menambah Pendapatan ' . $request->jenis . ' Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.data_laporan')->with($notifikasi);
    }
}
