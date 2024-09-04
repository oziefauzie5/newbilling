<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Transaksi\Transaksi;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Kendaraan;
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
    public function jurnal()
    {
        $data['bulan'] = strtoupper(date('F', strtotime(Carbon::now())));
        $whereMonth = date('m', strtotime(Carbon::now()));
        $data['kredit'] = Jurnal::sum('jurnal_kredit');
        $data['debet'] = Jurnal::sum('jurnal_debet');
        $data['jurnal'] = Jurnal::select('jurnals.*', 'jurnals.created_at as tgl_trx', 'setting_akuns.*')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'jurnals.jurnal_metode_bayar')
            ->get();
        $data['kendaraan'] = (new GlobalController)->data_kendaraan()->get();
        $data['user'] = (new GlobalController)->all_user()->get();
        $data['setting_akun'] = (new GlobalController)->setting_akun()->get();
        return view('Transaksi/jurnal', $data);
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
        // dd($id);
        return response()->download(storage_path('storage/bukti-transaksi/' . $id));
    }
}
