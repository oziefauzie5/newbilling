<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Transaksi\Transaksi;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Kasbon;
use App\Models\Transaksi\Kendaraan;
use App\Models\Transaksi\Pinjaman;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $data['bulan'] = strtoupper(date('F', strtotime(Carbon::now())));
        $whereMonth = date('m', strtotime(Carbon::now()));
        $query = Transaksi::whereMonth('created_at', $whereMonth);
        $data['transaksi'] = $query->get();
        $data['sum_pemasukan'] = $query->where('trx_kategori', 'PEMASUKAN')->sum('trx_total');
        $data['sum_pengeluaran'] = $query->where('trx_kategori', 'PENGELUARAN')->sum('trx_total');
        return view('Transaksi/transaksi', $data);
    }
    public function jurnal(Request $request)
    {
        // $data['bulan'] = strtoupper(date('F', strtotime(Carbon::now())));
        // $whereMonth = date('m', strtotime(Carbon::now()));
        $data['kategori'] = $request->query('kategori');
        $data['bulan'] = $request->query('bulan');
        $data['q'] = $request->query('q');
        $data['kredit'] = Jurnal::sum('jurnal_kredit');
        $data['debet'] = Jurnal::sum('jurnal_debet');
        $query = Jurnal::select('jurnals.*', 'jurnals.created_at as tgl_trx', 'setting_akuns.*')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'jurnals.jurnal_metode_bayar')
            ->where(function ($query) use ($data) {
                $query->where('jurnal_uraian', 'like', '%' . $data['q'] . '%');
                $query->orWhere('jurnal_admin', 'like', '%' . $data['q'] . '%');
                $query->orWhere('jurnal_tgl', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query->whereMonth('jurnals.created_at', date('m', strtotime($data['bulan'])))->whereYear('jurnals.created_at', date('Y', strtotime($data['bulan'])));


        if ($data['kategori'])
            $query->where('jurnal_kategori', '=', $data['kategori']);
        $data['jurnal'] = $query->paginate(20);


        $data['kendaraan'] = (new GlobalController)->data_kendaraan()->get();
        $data['user'] = (new GlobalController)->all_user()->get();
        $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'PEMBAYARAN')->get();
        return view('Transaksi/jurnal', $data);
    }
    public function pinjaman()
    {

        $data['kredit'] = 0;
        $data['debet'] = 0;
        $data['kasbon'] = Kasbon::select('kasbons.*', 'kasbons.created_at as tgl_trx', 'kasbons.id as pinjaman_id', 'users.*')
            ->join('users', 'users.id', '=', 'kasbons.kasbon_user_id')
            ->paginate(10);

        return view('Transaksi/pinjaman', $data);
    }
    public function store_jurnal_reimbuse(Request $request)
    {
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();
        $setting_biaya = (new GlobalController)->setting_biaya();

        $cek_saldo = Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_kredit') - Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_debet');

        if ($cek_saldo >= $setting_biaya->biaya_psb + $setting_biaya->biaya_sales) {

            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Reimburse Biaya ' . $request->jenis . ' ( ' . $request->plat_kendaraan . ' )';
            $data['jurnal_kategori'] = 'REIMBURSE';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = $request->metode;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_status'] = 1;
            $photo = $request->file('file');
            $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transaksi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['jurnal_img'] = $filename;
            Jurnal::create($data);
            $notifikasi = array(
                'pesan' => 'Reimburse ' . $request->jenis . ' Berhasil',
                'alert' => 'success',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Saldo tidak cukup',
                'alert' => 'error',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        }
    }
    public function store_jurnal_kasbon(Request $request)
    {
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();
        $data_user = (new GlobalController)->data_user($request->penerima);

        $cek_saldo = Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_kredit') - Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_debet');

        if ($cek_saldo >= $request->jumlah) {

            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Kasbon ' . $data_user->nama_user . ' Untuk keperluan ' . $request->uraian;
            $data['jurnal_kategori'] = 'PINJAMAN KARYAWAN';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = $request->metode;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_status'] = 1;
            $photo = $request->file('file');
            $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transaksi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['jurnal_img'] = $filename;
            Jurnal::create($data);

            $kasbon['kasbon_user_id'] = $request->penerima;
            $kasbon['kasbon_jenis'] = $request->jenis;
            $kasbon['kasbon_tempo'] = $request->tempo;
            $kasbon['kasbon_uraian'] = 'Kasbon ' . $data_user->nama_user . ' Untuk keperluan ' . $request->uraian;
            $kasbon['kasbon_debet'] = $request->jumlah;
            $kasbon['kasbon_file'] = $filename;
            $kasbon['kasbon_status'] = 0;

            Kasbon::create($kasbon);


            $notifikasi = array(
                'pesan' => 'Reimburse ' . $request->jenis . ' Berhasil',
                'alert' => 'success',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Saldo tidak cukup',
                'alert' => 'error',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        }
    }
    public function store_jurnal_pengeluaran(Request $request)
    {
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();
        $setting_biaya = (new GlobalController)->setting_biaya();

        $cek_saldo = Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_kredit') - Jurnal::where('jurnal_metode_bayar', $request->metode)->sum('jurnal_debet');

        if ($cek_saldo >= $setting_biaya->biaya_psb + $setting_biaya->biaya_sales) {


            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Reimburse Biaya ' . $request->jenis . ' ( ' . $request->plat_kendaraan . ' )';
            $data['jurnal_kategori'] = 'REIMBURSE';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = $request->metode;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_status'] = 1;

            $photo = $request->file('file');
            $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transaksi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['jurnal_img'] = $filename;
            Jurnal::create($data);
            $notifikasi = array(
                'pesan' => 'Reimburse ' . $request->jenis . ' Berhasil',
                'alert' => 'success',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Saldo tidak cukup',
                'alert' => 'error',
            );
            return redirect()->route('admin.lap.jurnal')->with($notifikasi);
        }
    }
    public function store_add_jurnal(Request $request)
    {
        // dd($request->uraian);
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();

        $data['jurnal_id'] = time();
        $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
        $data['jurnal_uraian'] = $request->uraian;
        $data['jurnal_kategori'] = $request->jenis;
        $data['jurnal_admin'] = $user['user_id'];
        $data['jurnal_metode_bayar'] = $request->metode;
        $data['jurnal_kredit'] = $request->jumlah;
        $data['jurnal_status'] = 1;
        $photo = $request->file('file');
        $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
        $path = 'bukti-transaksi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $data['jurnal_img'] = $filename;

        Jurnal::create($data);
        $notifikasi = array(
            'pesan' => 'Menambah Pendapatan ' . $request->jenis . ' Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.lap.jurnal')->with($notifikasi);
    }
    public function download_file($id)
    {
        $path = 'storage/bukti-transaksi/';
        $pathToFile = public_path($path . $id);
        return response()->download($pathToFile);
    }
}
