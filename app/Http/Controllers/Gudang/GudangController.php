<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Gudang\Data_Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GudangController extends Controller
{
    public function data_barang(Request $request)
    {
        // dd('t');
        $count = Data_Kategori::count();

        if ($count == 0) {
            $id_kate = 1;
        } else {
            $id_kate = $count + 1;
        }
        $data['id_kategori'] = '1' . sprintf("%03d", $id_kate);
        $count_barang = Data_Barang::count();

        if ($count_barang == 0) {
            $id_barang = 1;
        } else {
            $id_barang = $count_barang + 1;
        }
        $data['id_barang'] = '1' . sprintf("%03d", $id_barang);

        $data['kategori'] = Data_Kategori::all();
        // $data['supplier'] = supplier::all();
        // $data['q'] = $request->query('q');
        // // $sub_barang = SubBarang::where('id_subbarang', $data['q'])->orWhere('subbarang_mac', $data['q'])->orWhere('subbarang_nama', $data['q'])->first();
        // // if ($sub_barang) {
        // //     if ($data['q']) {
        // //         return redirect()->route('admin.barang.sub_barang', ['id' => $sub_barang->subbarang_idbarang . '?q=' . $data['q']]);
        // //     }
        // // } else {
        // //     $notifikasi = [
        // //         'pesan' => 'Kode Barang atau Mac Address tidak ditemukan',
        // //         'alert' => 'error',
        // //     ];
        // //     return redirect()->route('admin.barang.index')->with($notifikasi);
        // // }

        $data['tittle'] = 'Data Barang';
        $query = Data_Barang::orderBy('data__barangs.created_at', 'DESC');
        // $query = Data_Barang::join('suppliers', 'suppliers.id_supplier', '=', 'barangs.id_supplier')
        //     ->join('sub_barangs', 'sub_barangs.subbarang_idbarang', '=', 'barangs.id_barang')
        //     ->select('suppliers.supplier_nama', 'barangs.id_barang', 'barangs.barang_tgl_beli', 'sub_barangs.subbarang_idbarang', DB::raw('sum(subbarang_stok) as total')) #subbarang_stok
        //     ->groupBy('barangs.id_barang', 'suppliers.supplier_nama', 'sub_barangs.subbarang_idbarang', 'barangs.barang_tgl_beli')
        //     ->orderBy('barangs.created_at', 'DESC');
        $data['barang'] = $query->get();

        // $data['sub_barang'] = SubBarang::where('subbarang_status', '0')->get();

        return view('gudang/data_barang', $data);
    }

    public function store_kategori(Request $request)
    {
        $data['id_kategori'] = $request->id_kategori;
        $data['nama_kategori'] = $request->nama_kategori;
        $cek = Data_Kategori::where('nama_kategori', $request->nama_kategori)->first();
        if ($cek == null) {
            Data_Kategori::create($data);
            $notifikasi = array(
                'pesan' => 'Berhasil menambahkan Kategori',
                'alert' => 'success',
            );
            return redirect()->route('admin.gudang.data_barang')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal menambahkan Kategori. Kategori ' . $request->nama_kategori . ' sudah terdaftar. ',
                'alert' => 'error',
            );
            return redirect()->route('admin.gudang.data_barang')->with($notifikasi);
        }
    }
    public function store_barang(Request $request)
    {

        $y = date('y');
        $m = date('m');

        $penerima = (new GlobalController)->user_admin()['user_id'];
        $photo = $request->file('barang_img');
        $filename = date('d-m-Y', strtotime(Carbon::now())) . '_' . $request->barang_nama;
        $path = 'photo-tanda_terima_gudang/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        // $data['barang_img'] = $filename;
        // $data['barang_pengecek'] = $request->barang_pengecek;

        if ($request->barang_kategori == 'DROPCORE') {
            $qty = '1000';
            $harga = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'PIG8 2 CORE') {
            $qty = '1000';
            $harga = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'PIG8 >2 CORE') {
            $qty = '2000';
            $harga = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'ARMORE') {
            $qty = '2000';
            $harga = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'MINI ADSS') {
            $qty = '2000';
            $harga = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'ADSS 24 CORE') {
            $qty = '4000';
            $harga = $request->barang_harga / $qty;
        } else {
            $qty = '1';
            $harga = $request->barang_harga / $qty;
        }

        if ($request->barang_kategori == 'ONT') {
            $status = '5';
        } elseif ($request->barang_kategori == 'ADAPTOR') {
            $status = '5';
        } elseif ($request->barang_kategori == 'OLT') {
            $status = '5';
        } elseif ($request->barang_kategori == 'ROUTER') {
            $status = '5';
        } elseif ($request->barang_kategori == 'SWITCH') {
            $status = '5';
        } elseif ($request->barang_kategori == 'SPLICER') {
            $status = '5';
        } else {
            $status = '0';
        }

        for ($x = 0; $x < $request->barang_qty; $x++) {


            $data[] = [
                'barang_id' => mt_rand(10000, 99999),
                'barang_jenis' => $request->barang_jenis,
                'barang_lokasi' => $request->barang_lokasi,
                'barang_kategori' => $request->barang_kategori,
                'barang_nama' => $request->barang_nama,
                'barang_merek' => $request->barang_merek,
                'barang_qty' => $qty,
                'barang_satuan' => $request->barang_satuan,
                'barang_tglmasuk' => $request->barang_tglmasuk,
                'barang_harga' => $harga,
                'barang_status' => $status,
                'barang_ket' => $request->barang_ket,
                'barang_penerima' => $penerima,
                'barang_pengecek' => $penerima,
                'barang_nama_pengguna' => 'Gudang',
                'barang_img' => $filename,
            ];
        }

        // $data['barang_qty'] = $request->barang_qty;

        Data_Barang::insert($data);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.gudang.data_barang')->with($notifikasi);
    }

    public function stok_gudang()
    {


        // $query = Data_Kategori::orderBy('data__kategoris.id_kategori', 'ASC')
        //     ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__kategoris.nama_kategori')
        //     ->join('data__barangs', 'data__barangs.barang_kategori', '=', 'data__kategoris.nama_kategori')
        //     ->select('data__kategoris.id_kategori', 'data__kategoris.nama_kategori', 'data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barang_keluars.bk_kategori', DB::raw('sum(data__barang_keluars.bk_jumlah) as jumlah'), DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'))
        //     ->groupBy('data__kategoris.nama_kategori', 'data__kategoris.id_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barang_keluars.bk_kategori');

        // $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
        //     ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
        //     ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barang_keluars.bk_kategori', DB::raw('sum(data__barang_keluars.bk_jumlah) as jumlah'), DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'))
        //     ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barang_keluars.bk_kategori');

        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
            ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'),  DB::raw('sum(barang_digunakan) as digunakan'),  DB::raw('sum(barang_dijual) as dijual'),  DB::raw('sum(barang_rusak) as rusak'),  DB::raw('sum(barang_pengembalian) as kembali'))
            ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barangs.barang_kategori');


        // $query = Data_BarangKeluar::orderBy('data__barang_keluars.bk_kategori', 'ASC')
        //     ->leftjoin('data__barangs', 'data__barangs.barang_kategori', '=', 'data__barang_keluars.bk_kategori')
        //     ->select('data__barang_keluars.bk_kategori')
        //     ->selectRaw(DB::raw('sum(data__barang_keluars.bk_jumlah) as jumlah'))
        //     ->groupBy('data__barang_keluars.bk_kategori');



        $data['stok_gudang'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/stok_gudang', $data);
    }
    public function barang_keluar()
    {
        $data['tittle'] = 'Barang Keluar';

        $query = Data_BarangKeluar::orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC');

        $data['barang_keluar'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/barang_keluar', $data);
    }
}
