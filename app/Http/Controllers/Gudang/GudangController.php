<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingAplikasi;
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
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;

use function Laravel\Prompts\select;

class GudangController extends Controller
{
    public function data_barang(Request $request)
    {


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
        $d = date('d');
        $penerima = (new GlobalController)->user_admin()['user_id'];
        $photo = $request->file('barang_img');
        $filename = date('d-m-Y', strtotime(Carbon::now())) . '_' . $request->barang_nama;
        $path = 'photo-tanda_terima_gudang/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        // $data['barang_img'] = $filename;
        // $data['barang_pengecek'] = $request->barang_pengecek;

        if ($request->barang_kategori == 'DROPCORE') {
            $qty = '1000';
            $harga_satuan = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'PIG8 2 CORE') {
            $qty = '1000';
            $harga_satuan = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'PIG8 >2 CORE') {
            $qty = '2000';
            $harga_satuan = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'ARMORE') {
            $qty = '2000';
            $harga_satuan = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'MINI ADSS') {
            $qty = '2000';
            $harga_satuan = $request->barang_harga / $qty;
        } elseif ($request->barang_kategori == 'ADSS 24 CORE') {
            $qty = '4000';
            $harga_satuan = $request->barang_harga / $qty;
        } else {
            $qty = '1';
            $harga_satuan = $request->barang_harga / $qty;
        }

        if ($request->barang_kategori == 'ONT') {
            $status = '1';
        } elseif ($request->barang_kategori == 'ADAPTOR') {
            $status = '1';
        } elseif ($request->barang_kategori == 'OLT') {
            $status = '1';
        } elseif ($request->barang_kategori == 'ROUTER') {
            $status = '1';
        } elseif ($request->barang_kategori == 'SWITCH') {
            $status = '1';
        } elseif ($request->barang_kategori == 'SPLICER') {
            $status = '1';
        } else {
            $status = '0';
        }
        $barang_id_group = $y . $m . $d . mt_rand(1000, 9999);


        for ($x = 0; $x < $request->barang_qty; $x++) {



            $data[] = [
                'barang_id' => mt_rand(10000, 99999),
                'barang_id_group' => $barang_id_group,
                'barang_jenis' => $request->barang_jenis,
                'barang_lokasi' => $request->barang_lokasi,
                'barang_kategori' => $request->barang_kategori,
                'barang_nama' => $request->barang_nama,
                'barang_merek' => $request->barang_merek,
                'barang_qty' => $qty,
                'barang_digunakan' => '0',
                'barang_dijual' => '0',
                'barang_rusak' => '0',
                'barang_pengembalian' => '0',
                'barang_dicek' => $status,
                'barang_satuan' => $request->barang_satuan,
                'barang_tglmasuk' => date('Y-m-d', strtotime($request->barang_tglmasuk)),
                'barang_harga' => $request->barang_harga,
                'barang_harga_satuan' => $harga_satuan,
                'barang_status' => $status,
                'barang_ket' => $request->barang_ket,
                'barang_penerima' => $penerima,
                'barang_pengecek' => $penerima,
                'barang_nama_pengguna' => 'Gudang',
                'barang_img' => $filename,
                'created_at' => date('Y-m-d h:m:s', strtotime(Carbon::now())),
            ];
        }

        Data_Barang::insert($data);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.gudang.stok_gudang')->with($notifikasi);
    }

    public function data_kode_group()
    {
        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
            ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan',  'data__barangs.barang_id_group', 'data__barangs.barang_tglmasuk', DB::raw('count(data__barangs.barang_kategori) as total'))
            ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_tglmasuk', 'data__barangs.barang_kategori', 'data__barangs.barang_id_group');


        $data['data_kode_group'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/data_kode_group', $data);
    }

    public function print_kode($id)
    {
        $data['data_kode_group'] = Data_Barang::where('barang_id_group', $id)->where('barang_digunakan', '0')->get();
        $data['kode_group'] = Data_Barang::where('barang_id_group', $id)->first();
        return view('gudang/print_kode_barang', $data);
    }
    public function stok_gudang()
    {
        $count = Data_Kategori::count();

        if ($count == 0) {
            $id_kate = 1;
        } else {
            $id_kate = $count + 1;
        }
        $data['id_kategori'] = '1' . sprintf("%03d", $id_kate);
        $data['kategori'] = Data_Kategori::all();
        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
            ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', DB::raw('sum(data__barangs.barang_dicek) as dicek'), DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'),  DB::raw('sum(barang_digunakan) as digunakan'),  DB::raw('sum(barang_dijual) as dijual'),  DB::raw('sum(barang_rusak) as rusak'),  DB::raw('sum(barang_pengembalian) as kembali'))
            ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barangs.barang_kategori');


        $data['stok_gudang'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/stok_gudang', $data);
    }
    public function print_stok_gudang()
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        // $data['start_date'] = $request->start_date;
        // $data['end_date'] = $request->end_date;
        $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_kategori', '=', 'data__barangs.barang_kategori')
            ->select('data__barangs.barang_kategori', 'data__barangs.barang_satuan', 'data__barangs.barang_jenis', DB::raw('sum(data__barangs.barang_qty) as total'),  DB::raw('sum(barang_harga) as total_harga'),  DB::raw('sum(barang_digunakan) as digunakan'),  DB::raw('sum(barang_dijual) as dijual'),  DB::raw('sum(barang_rusak) as rusak'),  DB::raw('sum(barang_pengembalian) as kembali'))
            ->groupBy('data__barangs.barang_satuan', 'data__barangs.barang_jenis', 'data__barangs.barang_kategori');


        $data['stok_gudang'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/print_stok_gudang', $data);
    }
    public function barang_keluar()
    {
        $data['tittle'] = 'Barang Keluar';

        $query = Data_BarangKeluar::join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC');

        $data['barang_keluar'] = $query->get();

        return view('gudang/barang_keluar', $data);
    }
    public function data_group_barang_keluar()
    {
        $query = Data_BarangKeluar::orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC')
            ->select('data__barang_keluars.bk_id', 'data__barang_keluars.bk_waktu_keluar', 'data__barang_keluars.bk_keperluan', 'data__barang_keluars.bk_penerima', 'data__barang_keluars.bk_jenis_laporan',  DB::raw('count(data__barang_keluars.bk_id) as count'), DB::raw('sum(data__barang_keluars.bk_harga) as harga'))
            ->groupBy('data__barang_keluars.bk_keperluan', 'data__barang_keluars.bk_penerima', 'data__barang_keluars.bk_jenis_laporan', 'data__barang_keluars.bk_id', 'data__barang_keluars.bk_waktu_keluar');


        $data['data_group_bk'] = $query->get();
        // dd($data['stok_gudang']);
        return view('gudang/data_group_barang_keluar', $data);
    }
    public function print_skb(Request $request)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        $data['skb'] = $request->query('skb');
        $query = Data_BarangKeluar::join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC')
            ->where('bk_id', $data['skb']);
        $data['print_skb'] = $query->get();
        $query1 = Data_BarangKeluar::where('bk_id', $data['skb']);
        $data['data'] = $query1->first();
        $data['total'] = $query1->sum('bk_harga');
        // dd($data['print_skb']);
        return view('gudang/print_skb', $data);
        $pdf = App::make('dompdf.wrapper');
        $html = view('gudang/print_skb', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download($data['data']->bk_id . '.pdf');
    }
    public function form_barang_keluar()
    {
        $data['tittle'] = 'Barang Keluar';

        $query = Data_Barang::query();
        // $query = Data_Barang::orderBy('data__barangs.barang_kategori', 'ASC')
        //     ->select('data__barangs.*', DB::raw('sum(barang_qty) - sum(barang_digunakan) as total'))
        //     ->groupBy('barang_id');
        $data['data_barang'] = $query->get();
        $data['data_user'] = User::all();
        $data['id_admin'] = (new GlobalController)->user_admin()['user_id'];

        // dd($data['stok_gudang']);
        return view('gudang/form_barang_keluar', $data);
    }
    public function proses_form_barang_keluar(Request $request)
    {

        $no_sk = (new GlobalController)->no_surat_keterang();
        // $no_sk = 'SKB/250130/0005';
        $data_barang_keluar = Data_BarangKeluar::where('bk_id', $no_sk)->first();

        if ($data_barang_keluar) {
            return response()->json('failed');
        } else {
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
            $before = $request->before;
            $after = $request->after;
            $terpakai = $request->terpakai;
            $bk_waktu_keluar = $request->bk_waktu_keluar;
            // return response()->json($$request->all());
            for ($x = 0; $x < count($barang_id); $x++) {
                Data_BarangKeluar::create([
                    'bk_id' => $no_sk,
                    'bk_jenis_laporan' => $bk_jenis_laporan,
                    'bk_id_barang' => $barang_id[$x],
                    'bk_id_tiket' => $no_tiket,
                    'bk_kategori' => $barang_kategori[$x],
                    'bk_jumlah' => $jumlah_barang[$x],
                    'bk_before' => $before[$x],
                    'bk_after' => $after[$x],
                    'bk_terpakai' => $terpakai[$x] + $jumlah_barang[$x],
                    'bk_keperluan' => $bk_keperluan,
                    'bk_waktu_keluar' => date('Y-m-d', strtotime($bk_waktu_keluar)),
                    'bk_admin_input' => $admin,
                    'bk_penerima' => $bk_penerima,
                    'bk_status' => 0,
                    'bk_keterangan' => '',
                    'bk_harga' => $jumlah_harga[$x],
                ]);
                Data_Barang::whereIn('barang_id', [$barang_id[$x]])->update(
                    [
                        'barang_nama_pengguna' => $bk_jenis_laporan,
                        'barang_digunakan' => $terpakai[$x] + $jumlah_barang[$x],
                        'barang_status' => '1',
                    ]
                );
            }
            // Data_Tiket::create([
            //     'tiket_id' => $no_tiket,
            //     'tiket_kode' => 'T-' . $no_tiket,
            //     'tiket_site' => $tiket_site,
            //     'tiket_type' => $tiket_type,
            //     'tiket_jenis' => $bk_jenis_laporan,
            //     'tiket_status' => 'NEW',
            //     'tiket_nama' => $bk_keperluan,
            //     'tiket_keterangan' => $bk_keperluan,
            //     'tiket_pembuat' => $admin,
            // ]);

            return response()->json('success');
            // }
        }
        // $no_sk = 
    }
    public function proses_tiket_form_barang_keluar(Request $request)
    {

        $no_sk = (new GlobalController)->no_surat_keterang();
        $data_barang_keluar = Data_BarangKeluar::where('bk_id', $no_sk)->first();

        if ($data_barang_keluar) {
            return response()->json('failed');
        } else {
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
            $before = $request->before;
            $after = $request->after;
            $terpakai = $request->terpakai;
            $tiket_idpel = $request->tiket_idpel;
            for ($x = 0; $x < count($barang_id); $x++) {
                Data_BarangKeluar::create([
                    'bk_id' => $no_sk,
                    'bk_jenis_laporan' => $tiket_jenis,
                    'bk_id_barang' => $barang_id[$x],
                    'bk_id_tiket' => $tiket_id,
                    'bk_idpel' => $tiket_idpel,
                    'bk_kategori' => $barang_kategori[$x],
                    'bk_jumlah' => $jumlah_barang[$x],
                    'bk_before' => $before[$x],
                    'bk_after' => $after[$x],
                    'bk_terpakai' => $terpakai[$x] + $jumlah_barang[$x],
                    'bk_keperluan' => $tiket_tindakan,
                    'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                    'bk_admin_input' => $admin,
                    'bk_penerima' => $tiket_teknisi1,
                    'bk_status' => 0,
                    'bk_harga' => $jumlah_harga[$x],
                ]);
                Data_Barang::whereIn('barang_id', [$barang_id[$x]])->update(
                    [
                        'barang_nama_pengguna' => $tiket_jenis,
                        'barang_digunakan' => $terpakai[$x] + $jumlah_barang[$x],
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
        }
    }
}
