<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Imports\Import\barangimport;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Barang\Barang;
use App\Models\Barang\SubBarang;
use App\Models\Barang\Kategori;
use App\Models\Barang\supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use mPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Import\InputDataImport;
use Illuminate\Support\Facades\App;

class BarangController extends Controller
{

    public function index(Request $request)
    {
        // dd('t');
        $count = Kategori::count();

        if ($count == 0) {
            $id_kate = 1;
        } else {
            $id_kate = $count + 1;
        }
        $data['id_kategori'] = '1' . sprintf("%03d", $id_kate);
        $count = supplier::count();
        if ($count == 0) {
            $id_supplier = 1;
        } else {
            $id_supplier = $count + 1;
        }
        $data['id_supplier'] = '1' . sprintf("%03d", $id_supplier);
        $data['kategori'] = Kategori::all();
        $data['supplier'] = supplier::all();
        $data['q'] = $request->query('q');
        // $sub_barang = SubBarang::where('id_subbarang', $data['q'])->orWhere('subbarang_mac', $data['q'])->orWhere('subbarang_nama', $data['q'])->first();
        // if ($sub_barang) {
        //     if ($data['q']) {
        //         return redirect()->route('admin.barang.sub_barang', ['id' => $sub_barang->subbarang_idbarang . '?q=' . $data['q']]);
        //     }
        // } else {
        //     $notifikasi = [
        //         'pesan' => 'Kode Barang atau Mac Address tidak ditemukan',
        //         'alert' => 'error',
        //     ];
        //     return redirect()->route('admin.barang.index')->with($notifikasi);
        // }

        $data['tittle'] = 'Data Barang';
        $query = Barang::join('suppliers', 'suppliers.id_supplier', '=', 'barangs.id_supplier')
            ->join('sub_barangs', 'sub_barangs.subbarang_idbarang', '=', 'barangs.id_barang')
            ->select('suppliers.supplier_nama', 'barangs.id_barang', 'barangs.barang_tgl_beli', 'sub_barangs.subbarang_idbarang', DB::raw('sum(subbarang_stok) as total')) #subbarang_stok
            ->groupBy('barangs.id_barang', 'suppliers.supplier_nama', 'sub_barangs.subbarang_idbarang', 'barangs.barang_tgl_beli')
            ->orderBy('barangs.created_at', 'DESC');
        $data['barang'] = $query->get();

        $data['sub_barang'] = SubBarang::where('subbarang_status', '0')->get();

        return view('barang/barang', $data);
    }

    public function cari_barang($id)
    {
        $data =  SubBarang::where('id_subbarang', $id)->first();
        return response()->json($data);
    }
    public function barang_keluar(Request $request)
    {
        $admin_nama = Auth::user()->name;
        if ($request->id_subbarang) {
            $data['subbarang_stok'] = '0';
            $data['subbarang_status'] = '1';
            $data['subbarang_keluar'] = '1';
            $data['subbarang_admin'] = $admin_nama;
            $data['subbarang_keterangan'] = $request->subbarang_keterangan;
            $data['subbarang_deskripsi'] = $request->subbarang_deskripsi;
            $data['subbarang_tgl_keluar'] = date('Y-m-d H:m:s', strtotime(Carbon::now()));
            $data =  SubBarang::where('id_subbarang', $request->id_subbarang)->update($data);
            $notifikasi = [
                'pesan' => 'Berhasil',
                'alert' => 'success',
            ];
            return redirect()->route('admin.barang.sub_barang', ['id' => $request->subbarang_idbarang])->with($notifikasi);
        }
    }

    public function sub_barang(Request $request, $id)
    {

        // SubBarang::where('subbarang_idbarang','512878')->where('subbarang_ktg','ONT')->update(['subbarang_idbarang'=>'442585']);
        // dd('berhasil');

        $data['tittle'] = 'Sub Barang';
        $data['kategori'] = Kategori::all();
        $data['q'] = $request->query('q');
        $data['status'] = $request->query('status');
        if ($data['status'] == 'Belum Terpakai') {
            $nilai = '>=';
            $value = '1';
        } elseif ($data['status'] == 'Terpakai') {
            $nilai = '<=';
            $value = '0';
        } else {
            $nilai = '=';
        }
        $query = SubBarang::join('barangs', 'barangs.id_barang', '=', 'sub_barangs.subbarang_idbarang')
            ->join('suppliers', 'suppliers.id_supplier', '=', 'barangs.id_supplier')
            ->orderBy('subbarang_nama', 'ASC', 'subbarang_tgl_masuk', 'ASC')
            ->where('sub_barangs.subbarang_idbarang', $id)
            ->where(function ($query) use ($data) {
                $query->where('id_subbarang', 'like', '%' . $data['q'] . '%');
                $query->orWhere('subbarang_mac', 'like', '%' . $data['q'] . '%');
                $query->orWhere('subbarang_nama', 'like', '%' . $data['q'] . '%');
            });

        if ($data['status'])
            $query->where('sub_barangs.subbarang_stok', $nilai, $value);


        $data['SubBarang'] = $query->paginate(20);
        $data['idbarang'] = $id;

        return view('barang/sub_barang', $data);
    }

    public function rekap_barang($id)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        $data['data_barang'] = Barang::select('barangs.*', 'sub_barangs.*', 'sub_barangs.updated_at as tgl_digunakan')
            ->join('sub_barangs', 'sub_barangs.subbarang_idbarang', '=', 'barangs.id_barang')
            ->orderBy('subbarang_nama', 'ASC')
            ->where('barangs.id_barang', $id)
            ->get();
        $data['invoice'] = Barang::join('suppliers', 'suppliers.id_supplier', '=', 'barangs.id_supplier')
            ->where('barangs.id_barang', $id)
            ->get();
        $data['barang'] = SubBarang::distinct()->where('subbarang_idbarang', $id)->get(['subbarang_nama']);
        // dd($hitung);
        // return view('barang/rekap_barang', $data);
        $pdf = App::make('dompdf.wrapper');
        $html = view('barang/rekap_barang', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Rekap Barang.pdf');
    }

    // public function input_subbarang(Request $request, $id)
    // {
    //     // dd($request->stok);
    //     $sub['subbarang_stok'] = $request->stok;
    //     if ($sub['subbarang_stok'] == '1') {
    //         $sub['subbarang_keluar'] = '0';
    //     } elseif ($sub['subbarang_stok'] == '0') {
    //         $sub['subbarang_keluar'] = '1';
    //     }
    //     $sub['subbarang_keterangan'] = $request->ket;
    //     $sub['subbarang_sn'] = $request->sn;
    //     $sub['subbarang_mac'] = $request->mac;
    //     SubBarang::where('id_subbarang', $id)->update($sub);
    //     $notifikasi = array(
    //         'pesan' => 'Berhasil input data',
    //         'alert' => 'success',
    //     );
    //     return redirect()->route('admin.barang.sub_barang', ['id' => $request->idbarang])->with($notifikasi);
    // }
    // public function update_status_barang(Request $request, $id)
    // {
    // if ($request->ket == 'Rusak') {
    //     $sub['subbarang_keterangan'] = $request->ket;
    //     $sub['subbarang_deskripsi'] = $request->ket;
    //     $sub['subbarang_status'] = '10';
    //     $sub['subbarang_stok'] = '0';
    //     $sub['subbarang_keluar'] = '0';
    // } elseif ($request->ket == 'Dalam Pengecekan') {
    //     $sub['subbarang_keterangan'] = $request->ket;
    //     $sub['subbarang_status'] = '5';
    //     $sub['subbarang_stok'] = '1';
    //     $sub['subbarang_keluar'] = '0';
    // } elseif ($request->ket == 'Barang Normal') {
    //     $sub['subbarang_keterangan'] = $request->ket;
    //     $sub['subbarang_status'] = '0';
    //     $sub['subbarang_stok'] = '1';
    //     $sub['subbarang_keluar'] = '0';
    // }
    // $sub['subbarang_deskripsi'] = $request->desk;

    // // dd($sub);
    // SubBarang::where('id_subbarang', $id)->update($sub);
    // $notifikasi = array(
    //     'pesan' => 'Berhasil Update Status Barang',
    //     'alert' => 'success',
    // );
    // return redirect()->route('admin.barang.sub_barang', ['id' => $request->idbarang])->with($notifikasi);
    // }


    public function store(Request $request)
    {
        // dd($request->qty);
        $id_barang = mt_rand(100000, 999999);
        Barang::create([
            'id_barang' => $id_barang,
            'id_trx' => $request->id_trx,
            'id_supplier' => $request->id_supplier,
            'barang_tgl_beli' => $request->tgl_beli,
        ]);

        if ($request->nama_kategori == 'DROPCORE') {
            $qty = '1000';
        } elseif ($request->nama_kategori == 'PIG8 2 CORE') {
            $qty = '1000';
        } elseif ($request->nama_kategori == 'PIG8 >2 CORE') {
            $qty = '2000';
        } elseif ($request->nama_kategori == 'ARMORE') {
            $qty = '2000';
        } else {
            $qty = '1';
        }


        for ($x = 0; $x < $request->qty; $x++) {
            $units[] = [
                "id_subbarang" => mt_rand(100000, 999999),
                "subbarang_idbarang" => $id_barang,
                "subbarang_nama" => strtoupper($request->barang_nama),
                "subbarang_ktg" => $request->nama_kategori,
                "subbarang_qty" => $qty,
                "subbarang_keluar" => '0',
                "subbarang_stok" => $qty,
                "subbarang_harga" => $request->harga,
                "subbarang_tgl_masuk" => $request->tgl_beli,
                "subbarang_status" => '5',
            ];
        }

        // dd($units);

        $created = SubBarang::insert($units);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.index')->with($notifikasi);
    }
    public function store_subbarang(Request $request)
    {

        if ($request->nama_kategori == 'DROPCORE') {
            $qty = '1000';
        } elseif ($request->nama_kategori == 'PIG8 2 CORE') {
            $qty = '1000';
        } elseif ($request->nama_kategori == 'PIG8 >2 CORE') {
            $qty = '2000';
        } elseif ($request->nama_kategori == 'ARMORE') {
            $qty = '2000';
        } else {
            $qty = '1';
        }


        for ($x = 0; $x < $request->qty; $x++) {
            $units[] = [
                "id_subbarang" => mt_rand(100000, 999999),
                "subbarang_idbarang" => $request->idbarang,
                "subbarang_nama" => strtoupper($request->barang_nama),
                "subbarang_ktg" => $request->nama_kategori,
                "subbarang_qty" => $qty,
                "subbarang_keluar" => '0',
                "subbarang_stok" => $qty,
                "subbarang_harga" => $request->harga,
                "subbarang_tgl_masuk" => $request->subbarang_tgl_masuk,
                "subbarang_status" => '4',
            ];
        }

        // dd($units);

        $created = SubBarang::insert($units);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.sub_barang', ['id' => $request->idbarang])->with($notifikasi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        Barang::where('id_barang', $id)->update([
            'barang_kategori' => $request->id_kategori,
            'barang_tgl_beli' => $request->tgl_beli,
            'id_trx' => $request->id_trx,
            // 'barang_total'=>$request->total,
        ]);
        $jumlah =  $request->qty;




        $created = SubBarang::where('subbarang_idbarang', $id)->update([
            "subbarang_id_trx" => $request->id_trx,
        ]);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.index')->with($notifikasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = SubBarang::where('subbarang_idbarang', $id);
        if ($data) {
            $data->delete();
        }
        $dataa = Barang::where('id_barang', $id);
        if ($dataa) {
            $dataa->delete();
        }
        $notifikasi = array(
            'pesan' => 'Berhasil menghapus barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.index')->with($notifikasi);
    }
    public function destroy_subbarang(string $id)
    {
        $data = SubBarang::where('id_subbarang', $id);

        $data_subbarang = $data->first();
        if ($data) {
            $data->delete();
        }
        $notifikasi = array(
            'pesan' => 'Berhasil menghapus barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.sub_barang', ['id' => $data_subbarang->subbarang_idbarang])->with($notifikasi);
    }

    public function print_kode_barang($id)
    {
        // $data['kategori'] = Kategori::all();
        // $data['SubBarang'] = SubBarang::join('barangs', 'barangs.id_barang', '=', 'sub_barangs.subbarang_idbarang')
        //     ->join('suppliers', 'suppliers.id_supplier', '=', 'barangs.id_supplier')
        //     ->orderBy('subbarang_nama', 'ASC', 'subbarang_tgl_masuk', 'ASC')
        //     ->where('sub_barangs.subbarang_idbarang', $id)->get();
        // $data['idbarang'] = $id;

        $data['kode_barang'] = SubBarang::where('subbarang_idbarang', $id)->get();
        // dd($data['kode_barang']->id_subbarang);

        return view('barang/kode_barang', $data);
    }

    public function barang_import(Request $request)
    {
        // dd($request->test);
        // dd('cek1');
        Excel::import(new barangimport(), $request->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.barang.index')->with($notifikasi);
    }
}
