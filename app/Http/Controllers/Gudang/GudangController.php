<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Gudang\Data_Kategori;
use App\Models\Gudang\Data_Keranjang;
use App\Models\Tiket\Data_Tiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;

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
        $data['kategori'] = Data_Kategori::all();
        $data['tittle'] = 'Data Barang';

        $data['find_kate'] = $request->query('find_kate');

        $query = Data_Barang::orderBy('data__barangs.created_at', 'DESC');
        if ($data['find_kate'])
            $query->where('barang_kategori', '=', $data['find_kate']);
        $data['barang'] = $query->get();

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
        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
            ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'),  DB::raw('sum(barang_digunakan) as digunakan'),  DB::raw('sum(barang_dijual) as dijual'),  DB::raw('sum(barang_rusak) as rusak'),  DB::raw('sum(barang_pengembalian) as kembali'))
            ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barangs.barang_kategori');


        $data['stok_gudang'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/stok_gudang', $data);
    }
    public function barang_keluar()
    {
        $data['tittle'] = 'Barang Keluar';

        $query = Data_BarangKeluar::orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC');

        $data['barang_keluar'] = $query->get();

        return view('gudang/barang_keluar', $data);
    }
    public function form_barang_keluar()
    {
        $data['tittle'] = 'Barang Keluar';

        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            ->select('data__barangs.*', DB::raw('sum(barang_qty) - sum(barang_digunakan) as total'))
            ->groupBy('barang_id');
        $data['data_barang'] = $query->get();
        $data['data_user'] = User::all();
        $data['id_admin'] = (new GlobalController)->user_admin()['user_id'];

        // dd($data['stok_gudang']);
        return view('gudang/form_barang_keluar', $data);
    }
    public function proses_form_barang_keluar(Request $request)
    {

        $no_sk = (new GlobalController)->no_surat_keterang();
        $no_tiket = (new GlobalController)->nomor_tiket();
        // $barang_id = ['aple', 'manggan', 'jeruk'];
        $admin = Auth::user()->id;
        $barang_id = $request->barang_id;
        $jumlah_harga = $request->jumlah_harga;
        $jumlah_barang = $request->jumlah_barang;
        $bk_penerima = $request->bk_penerima;
        $bk_jenis_laporan = $request->bk_jenis_laporan;
        $bk_keperluan = $request->bk_keperluan;
        $barang_kategori = $request->barang_kategori;
        $barang_nama = $request->barang_nama;
        $tiket_type = $request->tiket_type;
        $tiket_site = $request->tiket_site;
        for ($x = 0; $x < count($barang_id); $x++) {
            Data_BarangKeluar::create([
                'bk_id' => $no_sk,
                'bk_jenis_laporan' => $bk_jenis_laporan,
                'bk_id_barang' => $barang_id[$x],
                'bk_id_tiket' => $no_tiket,
                'bk_kategori' => $barang_kategori[$x],
                'bk_jumlah' => $jumlah_barang[$x],
                'bk_keperluan' => $bk_keperluan,
                'bk_file_bukti' => '-',
                'bk_nama_pengguna' => '',
                'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                'bk_admin_input' => $admin,
                'bk_penerima' => $bk_penerima,
                'bk_status' => 0,
                'bk_keterangan' => '',
                'bk_harga' => $jumlah_harga[$x],
            ]);
            Data_Barang::whereIn('barang_id', [$barang_id[$x]])->update(
                [
                    'barang_nama_pengguna' => $bk_jenis_laporan,
                    'barang_digunakan' => $jumlah_barang[$x],
                    'barang_status' => '2',
                ]
            );
        }
        Data_Tiket::create([
            'tiket_id' => $no_tiket,
            'tiket_kode' => 'T-' . $no_tiket,
            'tiket_site' => $tiket_site,
            'tiket_type' => $tiket_type,
            'tiket_jenis' => $bk_jenis_laporan,
            'tiket_status' => 'NEW',
            'tiket_nama' => $bk_keperluan,
            'tiket_keterangan' => $bk_keperluan,
            'tiket_pembuat' => $admin,
        ]);

        return response()->json($barang_id);
        // }
    }
    public function proses_tiket_form_barang_keluar(Request $request)
    {

        $no_sk = (new GlobalController)->no_surat_keterang();
        $admin = Auth::user()->id;
        $barang_id = $request->barang_id;
        $tiket_id = $request->tiket_id;
        $jumlah_harga = $request->jumlah_harga;
        $jumlah_barang = $request->jumlah_barang;
        $tiket_teknisi1 = $request->tiket_teknisi1;
        $tiket_jenis = $request->tiket_jenis;
        $tiket_tindakan = $request->tiket_tindakan;
        $barang_kategori = $request->barang_kategori;
        $tiket_status = $request->tiket_status;
        $tiket_type = $request->tiket_type;
        $tiket_site = $request->tiket_site;
        for ($x = 0; $x < count($barang_id); $x++) {
            Data_BarangKeluar::create([
                'bk_id' => $no_sk,
                'bk_jenis_laporan' => $tiket_jenis,
                'bk_id_barang' => $barang_id[$x],
                'bk_id_tiket' => $tiket_id,
                'bk_kategori' => $barang_kategori[$x],
                'bk_jumlah' => $jumlah_barang[$x],
                'bk_keperluan' => $tiket_tindakan,
                'bk_file_bukti' => '-',
                'bk_nama_pengguna' => '',
                'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                'bk_admin_input' => $admin,
                'bk_penerima' => $tiket_teknisi1,
                'bk_status' => 0,
                'bk_keterangan' => '',
                'bk_harga' => $jumlah_harga[$x],
            ]);
            Data_Barang::whereIn('barang_id', [$barang_id[$x]])->update(
                [
                    'barang_nama_pengguna' => $tiket_jenis,
                    'barang_digunakan' => $jumlah_barang[$x],
                    'barang_status' => 1,
                ]
            );
        }
        Data_Tiket::where('tiket_id', $tiket_id)->update(
            [
                'tiket_idbarang_keluar' => $no_sk,
            ]
        );

        return response()->json($no_sk);
        // }
    }
}
