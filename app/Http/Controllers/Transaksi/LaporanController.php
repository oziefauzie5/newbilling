<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Mitra\Mutasi;
use App\Models\Model_Has_Role;
use App\Models\Transaksi\DataLaporan;
use App\Models\Transaksi\Laporan;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Transaksi\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
class LaporanController extends Controller
{
    public function laporan_harian(Request $request)
    {
        // $date = Carbon::now();
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;
        $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->get();
        $ids = [1, 2, 5, 10, 13, 14];
        $biller = [10, 13, 14];
        $data['dat'] = "Laporan";
        $role = Model_Has_Role::where('model_id', $data['admin_user'])->first();
        $data['sum_role'] = Laporan::join('users','users.id','=','laporans.lap_admin')
                    ->join('model_has_roles','model_has_roles.model_id','=','users.id')
                    ->join('roles','roles.id','=','model_has_roles.role_id')
                    ->whereIn('model_has_roles.role_id',[2,5,10,13,14])
                    ->where('laporans.lap_status', '0')
                    ->select([
                        DB::raw('sum(laporans.lap_jumlah) as sum_jumlah'),
                        DB::raw('count(laporans.lap_jumlah) as count_trx'),
                        'roles.name',
                        ])
                    ->groupBy('roles.name')
                    ->get();
        $data['serah_terima'] = Laporan::join('users','users.id','=','laporans.lap_admin')
                    // ->join('model_has_roles','model_has_roles.model_id','=','users.id')
                    // ->join('roles','roles.id','=','model_has_roles.role_id')
                    // ->whereIn('model_has_roles.role_id',[2])
                    ->where('laporans.lap_status', '0')
                    ->where('laporans.lap_admin', $data['admin_user'])
                    ->select([
                        DB::raw('sum(laporans.lap_jumlah) as sum_jumlah'),
                        DB::raw('count(laporans.lap_jumlah) as count_trx'),
                        'users.name',
                        ])
                    ->groupBy('users.name')
                    ->first();
        // dd($data['serah_terima']);


        $data['now'] = date('Y-m-d', strtotime(carbon::now()));
        // $year =  $date->format('Y');
        // $month =  $date->format('m');
        $start_date = date('Y-m-01', strtotime(carbon::now()));
        $end_date = date('Y-m-24', strtotime(carbon::now()));
        $data['akun'] = SettingAkun::get();
        $data['adm'] = $request->query('adm');
        $data['q'] = $request->query('q');
        $data['ak'] = $request->query('ak');
        if ($role->role_id == 1) {
            $data['admin'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->whereIn('model_has_roles.role_id', $ids)->get();
            $query = Laporan::orderBy('laporans.created_at', 'DESC')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->where(function ($query) use ($data) {
                $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
            });
        } elseif($role->role_id == 8){
            $data['admin'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->whereIn('model_has_roles.role_id', $ids)->get();
            $query = Laporan::orderBy('laporans.created_at', 'DESC')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->where(function ($query) use ($data) {
                $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
            });
        }else {
            $data['admin'] = User::where('id', $data['admin_user'])->get();
            $query = Laporan::orderBy('laporans.created_at', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
                ->where('laporans.lap_admin', '=', $data['admin_user'])
                ->whereDate('laporans.created_at', '<=',$end_date )
                ->where(function ($query) use ($data) {
                    $query->where('lap_keterangan', 'like', '%' . $data['q'] . '%');
                });
        }
        $data['buat_laporan'] = $query->where('lap_status', 0)->sum('laporans.lap_jumlah');

        if ($data['ak'])
            $query->where('setting_akuns.akun_nama', '=', $data['ak']);

        $data['laporan'] = $query->get();
        // $data['pendapatan'] = $query->where('lap_status', 0)->sum('laporans.lap_jumlah');
        // $data['biaya_adm'] = 0;
        // $data['count_trx'] = $query->where('lap_status', 0)->count();


        if ($role->role_id == 1) {
            $querysum = Laporan::orderBy('laporans.created_at', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun');
        } elseif($role->role_id == 8){
            $querysum = Laporan::orderBy('laporans.created_at', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun');
        }else {
            $querysum = Laporan::orderBy('laporans.created_at', 'DESC')
                ->join('users', 'users.id', '=', 'laporans.lap_admin')
                ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
                ->where('laporans.lap_admin', '=', $data['admin_user'])
                // ->where('laporans.lap_jenis_inv', '=', 'INVOICE')
                ->whereDate('laporans.created_at', '>=',$start_date )
                ->whereDate('laporans.created_at', '<=',$end_date );
        }

        // $data['sum_tunai'] = $querysum->where('lap_status', 0)->where('lap_jenis_inv', 'Debit')->sum('laporans.lap_jumlah');

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
        return redirect()->route('admin.trx.laporan_harian')->with($notifikasi);
    }
    public function topup(Request $request)
    {
        $query = Laporan::orderBy('laporans.created_at', 'DESC')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->where('laporans.corporate_id',Session::get('corp_id'))
            ->where('laporans.lap_jenis_inv','Credit')
            ->where('laporans.lap_inv','>' ,'0')
            ->select([
                'laporans.lap_id', 
                'laporans.*', 
                'laporans.created_at as lap_tgl',
                'setting_akuns.id as akun_id', 
                'setting_akuns.akun_nama', 
                'users.id as user_id', 
                'users.name'
            ])
            ->where('lap_admin', $request->user_admin);

        $data['laporan'] = $query->get();
        $data['sum'] = $query->get();
        $data['setting_akun'] =  SettingAkun::where('corporate_id',Session::get('corp_id'))->get();
        $data['admin'] = $request->user_admin;
        $data['data_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_type','TUNAI')->get();

        // dd($data);
        return view('Transaksi/topup', $data);
    }
    public function lap_topup(Request $request, $id)
    {

        $admin_id = Auth::user()->id;
        $aray = $id;

        $cek_user = User::where('corporate_id',Session::get('corp_id'))->where('name','TRIPAY')->select('id','name')->first();
        // return response()->json($cek_user);
        
        if ($id != $cek_user->id) {
            $query = Laporan::where('corporate_id',Session::get('corp_id'))->whereIn('lap_id', $request->checkboxtopup_value);
            $data['laporan'] = $query->get();
            $data['total'] = $query->sum('lap_jumlah');
            
            
            
            $invoice = (new GlobalController)->no_invoice_mitra();
            $count = Mutasi::where('corporate_id',Session::get('corp_id'))->count();
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
            // return response()->json($request->metode_bayar);
            
            $data['corporate_id'] = Session::get('corp_id');
            $data['mt_mts_id'] = $id;
            $data['mt_admin'] = $admin_user;
            $data['mt_kategori'] = 'TOPUP';
            $data['mt_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
            $data['mt_kredit'] = $data['total'];
            $data['mt_debet'] = 0;
            $data['mt_biaya_adm'] = 0;
            $data['mt_saldo'] = $total;
            $data['mt_cabar'] = $request->metode_bayar;
            
            Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI
            // return response()->json($data);
            // return response()->json($user);
        }
        
        Laporan::where('lap_admin', $id)->whereIn('lap_id', $request->checkboxtopup_value)->update([
                        'lap_admin' => $admin_id,
                    ]);


        $route = URL::to('/');
        return response()->json($route);
        // return redirect()->route('admin.trx.laporan_harian')->with($notifikasi);
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
        return redirect()->route('admin.trx.laporan_harian')->with($notifikasi);
    }
    public function buat_laporan(Request $request, $id)
    {
        $tanggal = (new GlobalController)->tanggal();
        $start_date = date('Y-m-01', strtotime(carbon::now()));
        $end_date = date('Y-m-24', strtotime(carbon::now()));
        $nama_admin = Auth::user()->name;
        $cek_id = DataLaporan::where('data_lap_id', $request->lap_id)->first();
        if ($cek_id) {
            $notifikasi = [
                'pesan' => 'Maaf, Anda gagal membuat laporan. silahkan ulangi kembali yah',
                'alert' => 'error',
            ];
            return redirect()->route('admin.trx.laporan_harian')->with($notifikasi);
        } else {
            $tgl = date('Y-m-d', strtotime(Carbon::now()));
            $data['corporate_id' ]= Session::get('corp_id');
            $data['data_lap_id'] = $request->lap_id;
            $data['data_lap_tgl'] = $tgl;
            $data['data_lap_pendapatan'] = $request->total;
            $data['data_lap_admin'] = $id;
            $data['data_lap_trx'] = $request->count_trx;
            
            $update_data['lap_status'] = 1;
            $update_data['lap_id'] = $request->lap_id;

            DataLaporan::create($data);
            Laporan::where('lap_status', '0')->where('lap_admin', $id)
            // ->whereDate('laporans.lap_tgl', '>=',$start_date )
            ->whereDate('laporans.lap_tgl', '<=',$end_date )
            ->update($update_data);
            // Jurnal::create($jurnal);


            $notifikasi = [
                'pesan' => 'Terimakasih. Laporan anda berhasil dibuat',
                'alert' => 'success',
            ];
            return redirect()->route('admin.trx.laporan_harian')->with($notifikasi);
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
                    $query->where('data_lap_admin', 'like', '%' . $data['q'] . '%');
                });
        } elseif ($role->role_id == 8){
            $data['admin'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->whereIn('model_has_roles.role_id', $ids)->get();
            $query = DataLaporan::orderBy('data_laporans.data_lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'data_laporans.data_lap_admin')
                ->where(function ($query) use ($data) {
                    $query->where('data_lap_admin', 'like', '%' . $data['q'] . '%');
                });
        } else {
            $data['admin'] = User::where('id', $data['admin_user'])->get();
            $query = DataLaporan::orderBy('data_laporans.data_lap_tgl', 'DESC')
                ->join('users', 'users.id', '=', 'data_laporans.data_lap_admin')
                ->where('data_laporans.data_lap_admin', '=', $data['admin_user'])
                ->where(function ($query) use ($data) {
                    $query->where('data_lap_admin', 'like', '%' . $data['q'] . '%');
                });
        }

        if ($data['start'])
            $query->whereDate('data_laporans.data_lap_tgl', '>=', $data['start']);
        if ($data['end'])
            $query->whereDate('data_laporans.data_lap_tgl', '<=', $data['end']);
        if ($data['adm'])
            $query->where('users.name', '=', $data['adm']);

        $data['laporan'] = $query->get();

        $data['pendapatan'] = $query->sum('data_laporans.data_lap_pendapatan');
        $data['count_trx'] = $query->sum('data_laporans.data_lap_trx');
        $data['count_data'] = $query->count();

        return view('Transaksi/data_laporan', $data);
    }

    public function data_lap_delete($id)
    {

        $data = DataLaporan::where('data_lap_id', $id);
        $laporan = Laporan::where('lap_id', $id);

        if ($data) {
            if ($laporan) {
                $laporan->update([
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

        $admin_lap = DataLaporan::where('data_lap_id', $id)->first();
        $nama_admin = (new GlobalController)->data_user($admin_lap->data_lap_admin);
        $whereDate = date('Y-m-d', strtotime(Carbon::now()));
        
        $query = Laporan::query()
            ->select('data_laporans.*', 'users.*', 'setting_akuns.*', 'laporans.*', 'laporans.created_at as tgl_trx')  
            ->orderBy('tgl_trx', 'DESC')
            ->join('data_laporans', 'data_laporans.data_lap_id', '=', 'laporans.lap_id')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->where('laporans.lap_id', '=', $id);
        $data['laporan'] = $query->get();
        
        $query1 = Laporan::query()
            ->join('data_laporans', 'data_laporans.data_lap_id', '=', 'laporans.lap_id')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->where('laporans.lap_id', '=', $id)
            ->select([
                    DB::raw('sum(laporans.lap_pokok) as pokok'),
                    DB::raw('sum(laporans.lap_ppn) as ppn'),
                    DB::raw('sum(laporans.lap_bph_uso) as bph_uso'),
                    DB::raw('sum(laporans.lap_fee_mitra) as fee_mitra'),
                    DB::raw('sum(laporans.lap_jumlah) as jumlah'),
                    ]);
        $data['sum_laporan'] = $query1->first();

        $query2 = Laporan::query()
            ->join('data_laporans', 'data_laporans.data_lap_id', '=', 'laporans.lap_id')
            ->join('users', 'users.id', '=', 'laporans.lap_admin')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'laporans.lap_akun')
            ->where('laporans.lap_id', '=', $id)
            ->select([
                    'setting_akuns.akun_nama',
                    DB::raw('sum(laporans.lap_jumlah) as jumlah'),
                    ])
            ->groupBy('setting_akuns.akun_nama');
        $data['sum_akun'] = $query2->first();

        // $data['sum_laporan'] = Laporan::join('users','users.id','=','laporans.lap_admin')
        //             ->where('laporans.lap_status', '0')
        //             ->select([
        //                 DB::raw('sum(laporans.lap_jumlah) as sum_jumlah'),
        //                 DB::raw('count(laporans.lap_jumlah) as count_trx'),
        //                 'roles.name',
        //                 ])
        //             ->groupBy('roles.name')
        //             ->get();
        

        $data['total'] = Laporan::where('lap_jenis_inv', 'Credit')->where('lap_id', $id)->sum('lap_jumlah') - Laporan::where('lap_jenis_inv', 'Debit')->where('lap_id', $id)->sum('lap_jumlah');
        $data['admin'] = $nama_admin->nama_user;

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
