<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Pesan\Pesan;
use App\Models\PSB\Registrasi;
use App\Models\Teknisi\Teknisi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Transaksi;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Kasbon;
use App\Models\Transaksi\Kendaraan;
use App\Models\Transaksi\LapMingguan;
use App\Models\Transaksi\Pinjaman;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $data['bulan'] = strtoupper(date('F', strtotime(Carbon::now())));
        $whereMonth = date('m', strtotime(Carbon::now()));
        $query = Transaksi::whereMonth('created_at', $whereMonth);
        $data['transaksi'] = $query->get();
        $data['sum_pemasukan'] = $query->where('trx_kategori', 'PENDAPATAN')->sum('trx_total');
        $data['sum_pengeluaran'] = $query->where('trx_kategori', 'PENGELUARAN')->sum('trx_total');
        return view('Transaksi/transaksi', $data);
    }
    public function jurnal(Request $request)
    {
        $data['kategori'] = $request->query('kategori');
        $data['bulan'] = $request->query('bulan');
        $data['start'] = $request->query('start');
        $data['end'] = $request->query('end');
        $data['q'] = $request->query('q');
        $data['akun'] = $request->query('akun');

        $query = Jurnal::select('jurnals.*', 'jurnals.created_at as tgl_trx', 'setting_akuns.*')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'jurnals.jurnal_metode_bayar')
            ->orderBy('tgl_trx', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('jurnal_uraian', 'like', '%' . $data['q'] . '%');
                $query->orWhere('jurnal_admin', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query->whereMonth('jurnals.created_at', date('m', strtotime($data['bulan'])))->whereYear('jurnals.created_at', date('Y', strtotime($data['bulan'])));
        if ($data['start'])
            $query->whereDate('jurnals.created_at', '>=', date('Y-m-d', strtotime($data['start'])))->whereDate('jurnals.created_at', '<=', date('Y-m-d', strtotime($data['end'])));
        if ($data['kategori'])
            $query->where('jurnal_kategori', '=', $data['kategori']);
        if ($data['akun'])
            $query->where('jurnal_metode_bayar', '=', $data['akun']);
        $data['jurnal'] = $query->paginate(20);
        $data['kredit'] = $query->where('jurnal_status', '=', '1')->sum('jurnal_kredit');
        // $data['debet'] = $query->where('jurnal_kategori', '=', 'PENGELUARAN')->sum('jurnal_debet');

        $query1 = Jurnal::select('jurnals.*', 'jurnals.created_at as tgl_trx', 'setting_akuns.*')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'jurnals.jurnal_metode_bayar')
            ->where(function ($query1) use ($data) {
                $query1->where('jurnal_uraian', 'like', '%' . $data['q'] . '%');
                $query1->orWhere('jurnal_admin', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query1->whereMonth('jurnals.created_at', date('m', strtotime($data['bulan'])))->whereYear('jurnals.created_at', date('Y', strtotime($data['bulan'])));
        if ($data['start'])
            $query1->whereDate('jurnals.created_at', '>=', date('Y-m-d', strtotime($data['start'])))->whereDate('jurnals.created_at', '<=', date('Y-m-d', strtotime($data['end'])));
        if ($data['kategori'])
            $query1->where('jurnal_kategori', '=', $data['kategori']);
        if ($data['akun'])
            $query1->where('jurnal_metode_bayar', '=', $data['akun']);
        // $data['jurnal'] = $query1->paginate(20);
        // $data['kredit'] = $query1->where('jurnal_kategori', '=', 'PENDAPATAN')->sum('jurnal_kredit');
        $data['debet'] = $query1->where('jurnal_status', '=', '1')->sum('jurnal_debet');



        // $queri1 = Jurnal::where('jurnal_kategori', '=', 'PENDAPATAN')
        //     ->where(function ($queri1) use ($data) {
        //         $queri1->where('jurnal_uraian', 'like', '%' . $data['q'] . '%');
        //         $queri1->orWhere('jurnal_admin', 'like', '%' . $data['q'] . '%');
        //         $queri1->orWhere('jurnal_tgl', 'like', '%' . $data['q'] . '%');
        //     });
        // if ($data['bulan'])
        //     $queri1->whereMonth('created_at', date('m', strtotime($data['bulan'])))->whereYear('created_at', date('Y', strtotime($data['bulan'])));
        // if ($data['start'])
        //     $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($data['start'])))->whereDate('created_at', '<=', date('Y-m-d', strtotime($data['end'])));
        // if ($data['akun'])
        //     $query->where('jurnal_metode_bayar', '=', $data['akun']);

        // $data['kredit'] = $queri1->sum('jurnal_kredit');



        $data['kendaraan'] = (new GlobalController)->data_kendaraan()->get();
        $data['user'] = (new GlobalController)->all_user()->get();
        $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'PEMBAYARAN')->get();
        $data['akun'] = (new GlobalController)->setting_akun()->where('id', '=', $data['akun'])->first();

        // $data['data_user'] = (new GlobalController)->user_admin();
        // $data['data_bank'] = (new GlobalController)->setting_akun()->where('id', '>', 1)->get();
        $data['data_biaya'] = (new GlobalController)->setting_biaya();
        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->orderBy('tgl', 'DESC')
            ->where('registrasis.reg_progres', '=', 4);
        $data['data_registrasi'] = $query->get();

        return view('Transaksi/jurnal', $data);
    }
    public function jurnal_print(Request $request, $id)
    {
        $data['admin'] = (new GlobalController)->user_admin();
        $data['data'] = LapMingguan::select('lap_mingguans.*', 'users.id as id_user', 'users.name')
            ->join('users', 'users.id', '=', 'lap_mingguans.lm_admin')
            ->where('lap_mingguans.lm_id', '=', $id)
            ->first();

        $exp = explode(" ", $data['data']->lm_periode);
        $dari = $exp[0];
        $sampai = $exp[2];
        $data['periode'] = $data['data']->lm_periode;
        // dd($sampai);

        $data['lap_mingguan'] = Jurnal::select('jurnals.*', 'jurnals.created_at as tgl_trx', 'setting_akuns.*')
            ->join('setting_akuns', 'setting_akuns.id', '=', 'jurnals.jurnal_metode_bayar')
            ->where('jurnals.jurnal_id', '=', $id)
            ->get();
        $data['debet'] = Jurnal::where('jurnal_id', '=', $id)
            ->sum('jurnal_debet');


        $data['kredit'] = Jurnal::where('jurnal_id', '=', $id)
            ->sum('jurnal_kredit');

        $query = Transaksi::whereDate('created_at', '>=', date('Y-m-d', strtotime($dari)))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($sampai)));
        $data['transaksi'] = $query->get();
        $data['transaksi_total'] = $query->sum('trx_total');
        $data['transaksi_count'] = $query->sum('trx_qty');

        $query_inv = Invoice::where('inv_jenis_tagihan', '!=', 'FREE')
            ->whereDate('inv_tgl_bayar', '>=', date('Y-m-d', strtotime($dari)))
            ->whereDate('inv_tgl_bayar', '<=', date('Y-m-d', strtotime($sampai)));
        $data['invoice_count'] = $query_inv->count();
        $data['inv_total'] = $query_inv->sum('inv_total');
        $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'PEMBAYARAN');

        // foreach ($data['setting_akun']->get() as $d) {
        //     $inv_total = Invoice::where('inv_akun', '=', $d->id)->sum('inv_total');


        //     echo '<table><tr><td>' . $d->akun_nama . '</td><td>' . $inv_total  . '</td></tr></table>';
        //     // dd($d->akun_nama);
        // }
        // dd('test');


        return view('Transaksi/print_jurnal', $data);
    }
    public function data_laporan_mingguan(Request $request)
    {
        // $data['kategori'] = $request->query('kategori');
        $data['bulan'] = $request->query('bulan');
        $data['q'] = $request->query('q');
        // $data['akun'] = $request->query('akun');

        $query = LapMingguan::select('lap_mingguans.*', 'users.id as id_user', 'users.name')
            ->join('users', 'users.id', '=', 'lap_mingguans.lm_admin')
            ->where(function ($query) use ($data) {
                $query->where('lm_admin', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query->whereMonth('created_at', date('m', strtotime($data['bulan'])))->whereYear('created_at', date('Y', strtotime($data['bulan'])));

        $data['laporan_mingguan'] = $query->paginate(20);






        // $data['kendaraan'] = (new GlobalController)->data_kendaraan()->get();
        // $data['user'] = (new GlobalController)->all_user()->get();
        // $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'PEMBAYARAN')->get();
        // $data['akun'] = (new GlobalController)->setting_akun()->where('id', '=', $data['akun'])->first();

        return view('Transaksi/data_laporan_mingguan', $data);
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
    public function jurnal_tutup_buku(Request $request)
    {
        $jurnal_id = time();
        $admin = (new GlobalController)->user_admin();
        $data['startdate'] = $request->startdate;
        $data['enddate'] = $request->enddate;
        $pendapatan = Jurnal::where('jurnal_status', '=', 1)
            ->whereDate('created_at', '>=', date('Y-m-d', strtotime($data['startdate'])))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($data['enddate'])))
            ->sum('jurnal_kredit');
        $pengeluaran = Jurnal::where('jurnal_status', '=', 1)
            ->whereDate('created_at', '>=', date('Y-m-d', strtotime($data['startdate'])))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($data['enddate'])))
            ->sum('jurnal_debet');

        // if ($pendapatan == 0 && $pengeluaran == 0) {
        //     dd('gagal');
        // } else {
        //     dd('berhasil');
        // }
        // dd('test');
        $data['lm_id'] = $jurnal_id;
        $data['lm_admin'] = $admin['user_id'];
        $data['lm_debet'] = $pendapatan;
        $data['lm_kredit'] = $pengeluaran;
        // $data['lm_adm'] = $request->data;
        $data['lm_akun'] = 2;
        $data['lm_periode'] = date('d-m-Y', strtotime($data['startdate'])) . ' - ' . date('d-m-Y', strtotime($data['enddate']));
        $data['lm_keterangan'] = 'Laporan Mingguan Admin

Periode : ' . date('d-m-Y', strtotime($data['startdate'])) . ' - ' . date('d-m-Y', strtotime($data['enddate'])) . '
Pembuat Laporan : ' . $admin['user_nama'] . '
Tanggal : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '';
        $data['lm_status'] = 1;
        // $photo = $request->file('file');
        // $filename = $admin['user_nama'] . $photo->getClientOriginalName();
        // $path = 'bukti-transaksi/' . $filename;
        // Storage::disk('public')->put($path, file_get_contents($photo));
        // $data['lm_img'] = $filename;
        LapMingguan::create($data);

        $update['jurnal_status'] = 10;
        $update['jurnal_id'] = $jurnal_id;
        Jurnal::where('jurnal_status', '=', 1)
            ->whereDate('created_at', '>=', date('Y-m-d', strtotime($data['startdate'])))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($data['enddate'])))
            ->update($update);
        $notifikasi = array(
            'pesan' => 'Laporan Mingguan Admin Berhasil dibuat',
            'alert' => 'success',
        );
        return redirect()->route('admin.lap.jurnal')->with($notifikasi);
    }
    public function store_jurnal_reimbuse(Request $request)
    {
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();
        $cek_saldo = (new GlobalController)->mutasi_jurnal();

        if ($cek_saldo['saldo'] >= $request->jumlah) {

            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Reimburse Biaya ' . $request->jenis . ' ( ' . $request->plat_kendaraan . ' )';
            $data['jurnal_kategori'] = 'PENGELUARAN';
            $data['jurnal_keterangan'] = 'REIMBURSE';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = 2;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_saldo'] = $cek_saldo['saldo'] - $request->jumlah;
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
        $cek_saldo = (new GlobalController)->mutasi_jurnal();

        if ($cek_saldo['saldo'] >= $request->jumlah) {

            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Kasbon ' . $data_user->nama_user . ' Untuk keperluan ' . $request->uraian;
            $data['jurnal_kategori'] = 'PENGELUARAN';
            $data['jurnal_keterangan'] = 'PINJAMAN KARYAWAN';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = 2;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_saldo'] = $cek_saldo['saldo'] - $request->jumlah;
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
        $cek_saldo = (new GlobalController)->mutasi_jurnal();

        if ($cek_saldo['saldo'] >= $request->jumlah) {


            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = $request->uraian;
            $data['jurnal_kategori'] = 'PENGELUARAN';
            $data['jurnal_keterangan'] = $request->jenis;
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = 2;
            $data['jurnal_debet'] = $request->jumlah;
            $data['jurnal_saldo'] = $cek_saldo['saldo'] - $request->jumlah;
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
    public function store_jurnal_pencairan(Request $request)
    {

        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();
        $cek_saldo = (new GlobalController)->mutasi_jurnal();
        $biaya = (new GlobalController)->setting_biaya();
        // dd($cek_saldo);

        if ($cek_saldo['saldo'] >= $request->jumlah) {


            $count = count($request->idpel);
            $psb = $biaya->biaya_psb * $count;
            $marketing = $biaya->biaya_sales * $count;

            $data['jurnal_id'] = time();
            $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data['jurnal_uraian'] = 'Pencairan PSB oleh ' . $user['user_nama'] . ' Sebanyak ' . $count . ' Pelanggan';
            $data['jurnal_kategori'] = 'PENGELUARAN';
            $data['jurnal_keterangan'] = 'PSB';
            $data['jurnal_admin'] = $user['user_id'];
            $data['jurnal_penerima'] = $request->penerima;
            $data['jurnal_metode_bayar'] = 2;
            $data['jurnal_debet'] = $psb;
            $data['jurnal_saldo'] = $cek_saldo['saldo'] - $psb;
            $data['jurnal_status'] = 1;


            $photo = $request->file('file');
            $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transaksi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['jurnal_img'] = $filename;
            Jurnal::create($data);


            $data1['jurnal_id'] = time();
            $data1['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
            $data1['jurnal_uraian'] = 'Pencairan MARKETING oleh ' . $user['user_nama'] . ' Sebanyak ' . $count . ' Pelanggan';
            $data1['jurnal_kategori'] = 'PENGELUARAN';
            $data1['jurnal_keterangan'] = 'MARKETING';
            $data1['jurnal_admin'] = $user['user_id'];
            $data1['jurnal_penerima'] = $request->penerima;
            $data1['jurnal_metode_bayar'] = 2;
            $data1['jurnal_debet'] = $marketing;
            $data1['jurnal_saldo'] = $cek_saldo['saldo'] - $marketing;
            $data1['jurnal_status'] = 1;
            $data1['jurnal_img'] = $filename;
            Jurnal::create($data1);
            Registrasi::where('reg_progres', '4')->whereIn('reg_idpel', $request->idpel)->update(['reg_progres' => '5']);
            Teknisi::whereIn('teknisi_idpel', $request->idpel)->where('teknisi_status', '1')->where('teknisi_job', 'PSB')->update(
                [
                    'teknisi_keuangan_userid' => $user['user_id'],
                    'teknisi_status' => 2,
                ]
            );

            $status = (new GlobalController)->whatsapp_status();

            if ($status->wa_status == 'Enable') {
                $pesan_group['status'] = '0';
            } else {
                $pesan_group['status'] = '10';
            }


            $pesan_group['ket'] = 'tiket';
            $pesan_group['target'] = '120363028776966861@g.us';
            $pesan_group['nama'] = 'GROUP TEKNISI OVALL';
            $pesan_group['pesan'] = '           -- PENCAIRAN DANA --

Pencairan dana berhasil 😊

Pelanggan : ' . $request->uraian . '
Jumlah Pencairan : ' . $count . '

Jumlah Pencairan : ' . number_format($request->jumlah) . '
Waktu Pencairan : ' . date('Y-m-d H:m:s', strtotime($tanggal)) . '

Dikeluarkan oleh: ' . $user['user_nama'] . '
Diterima oleh: ' . $request->penerima . '

    ';
            Pesan::create($pesan_group);

            $notifikasi = array(
                'pesan' => 'Pencairan Berhasil',
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

    public function store_topup_jurnal(Request $request)
    {
        $cek_saldo = (new GlobalController)->mutasi_jurnal();
        // dd($cek_saldo['saldo']);
        $tanggal = (new GlobalController)->tanggal();
        $user = (new GlobalController)->user_admin();

        $data['jurnal_id'] = time();
        $data['jurnal_tgl'] = date('Y-m-d H:m:s', strtotime($tanggal));
        $data['jurnal_uraian'] = $request->uraian;
        $data['jurnal_kategori'] = 'TOPUP';
        $data['jurnal_keterangan'] = 'TOPUP';
        $data['jurnal_admin'] = $user['user_id'];
        $data['jurnal_metode_bayar'] = 2;
        $data['jurnal_kredit'] = $request->jumlah;
        $data['jurnal_saldo'] = $cek_saldo['saldo'] + $request->jumlah;
        $data['jurnal_status'] = 1;
        $photo = $request->file('file');
        $filename = date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
        $path = 'bukti-transaksi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $data['jurnal_img'] = $filename;

        Jurnal::create($data);
        $notifikasi = array(
            'pesan' => 'Topup Berhasil',
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
