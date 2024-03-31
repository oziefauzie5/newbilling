<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Imports\Import\RegistrasiImport;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Barang\SubBarang;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;

class RegistrasiController extends Controller
{
    public function index()
    {
        $data['input_data'] = InputData::where('input_status', '0')->get();
        $data['data_router'] = Router::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();
        return view('Registrasi/registrasi', $data);
    }
    public function store(Request $request)
    {

        $router_nama = Router::whereId($request->reg_router)->first();
        $paket_nama = Paket::where('paket_id', $request->reg_profile)->first();

        Session::flash('reg_nama', $request->reg_nama);
        Session::flash('reg_idpel', $request->reg_idpel);
        Session::flash('reg_nolayanan', $request->reg_nolayanan);
        Session::flash('reg_hp', $request->reg_hp);
        Session::flash('reg_alamat_pasang', $request->reg_alamat_pasang);
        Session::flash('reg_maps', $request->reg_maps);
        Session::flash('reg_layanan', $request->reg_layanan);
        Session::flash('reg_router', $request->reg_router);
        Session::flash('router_nama', $router_nama->router_nama);
        Session::flash('reg_ip_address', $request->reg_ip_address);
        Session::flash('reg_username', $request->reg_username);
        Session::flash('reg_stt_perangkat', $request->reg_stt_perangkat);
        Session::flash('reg_mrek', $request->reg_mrek);
        Session::flash('reg_mac', $request->reg_mac);
        Session::flash('reg_sn', $request->reg_sn);
        Session::flash('reg_slotonu', $request->reg_slotonu);
        Session::flash('reg_odp', $request->reg_odp);
        Session::flash('kode_pactcore', $request->kode_pactcore);
        Session::flash('kode_adaptor', $request->kode_adaptor);
        Session::flash('kode_ont', $request->kode_ont);
        Session::flash('reg_tgl', $request->reg_tgl);
        Session::flash('reg_jenis_tagihan', $request->reg_jenis_tagihan);
        Session::flash('reg_harga', $request->reg_harga);
        Session::flash('reg_ppn', $request->reg_ppn);
        Session::flash('reg_dana_kerjasama', $request->reg_dana_kerjasama);
        Session::flash('input_subseles', $request->input_subseles);
        Session::flash('reg_kode_unik', $request->reg_kode_unik);
        Session::flash('reg_dana_kas', $request->reg_dana_kas);
        Session::flash('reg_catatan', $request->reg_catatan);
        Session::flash('reg_profile', $request->reg_profile);
        Session::flash('paket_nama', $paket_nama->paket_nama);

        $request->validate([
            'reg_nama' => 'required',
            'reg_idpel' => 'unique:registrasis,reg_idpel',
            'reg_nolayanan' => 'unique:registrasis,reg_nolayanan',
            'reg_hp' => 'required',
            'reg_alamat_pasang' => 'required',
            'reg_maps' => 'required',
            'reg_layanan' => 'required',
            'reg_router' => 'required',
            'reg_username' => 'required',
            'reg_stt_perangkat' => 'required',
            'reg_mrek' => 'required',
            'reg_mac' => 'required',
            'reg_sn' => 'required',
            'kode_pactcore' => 'required',
            'kode_adaptor' => 'required',
            'kode_ont' => 'required',
            'reg_jenis_tagihan' => 'required',
            'reg_harga' => 'required',
            'input_subseles' => 'required',
            'reg_profile' => 'required',
        ], [
            'reg_nama.required' => 'Nama tidak boleh kosong',
            'reg_idpel.unique' => 'Id Pelanggan sudah ada, Hapus input data terlebih dahulu',
            'reg_nolayanan.unique' => 'No layanan sudah ada, Ulangi Kembali',
            'reg_hp.required' => 'No Whatsapp tidak boleh kosong',
            'reg_alamat_pasang.required' => 'Alamat tidak boleh kosong',
            'reg_maps.required' => 'Maps tidak boleh kosong',
            'reg_layanan.required' => 'Layanan tidak boleh kosong',
            'reg_router.required' => 'Router tidak boleh kosong',
            'reg_username.required' => 'Username tidak boleh kosong',
            'reg_stt_perangkat.required' => 'Status Perangkat tidak boleh kosong',
            'reg_mrek.required' => 'Merek Perangkat tidak boleh kosong',
            'reg_mac.required' => 'Mac Address tidak boleh kosong',
            'reg_sn.required' => 'Serial Number Perangkat tidak boleh kosong',
            'kode_pactcore.required' => 'Kode Pactcore tidak boleh kosong',
            'kode_adaptor.required' => 'Kode Adaptor tidak boleh kosong',
            'kode_ont.required' => 'Kode ONT tidak boleh kosong',
            'reg_jenis_tagihan.required' => 'Jenis Tagihan tidak boleh kosong',
            'reg_harga.required' => 'Harga tidak boleh kosong',
            'input_subseles.required' => 'Sub Sales tidak boleh kosong',
            'reg_profile.required' => 'Paket tidak boleh kosong',
        ]);



        $data['reg_idpel'] = $request->reg_idpel;
        $data['reg_nolayanan'] = $request->reg_nolayanan;
        $data['reg_layanan'] = $request->reg_layanan;
        $data['reg_router'] = $request->reg_router;
        $data['reg_ip_address'] = $request->reg_ip_address;
        $data['reg_username'] = $request->reg_username;
        $data['reg_password'] = $request->reg_password;
        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
        $data['reg_mrek'] = $request->reg_mrek;
        $data['reg_mac'] = $request->reg_mac;
        $data['reg_sn'] = $request->reg_sn;
        $data['reg_slotonu'] = $request->reg_slotonu;
        $data['reg_odp'] = $request->reg_odp;
        $data['reg_kode_pactcore'] = $request->kode_pactcore;
        $data['reg_kode_adaptor'] = $request->kode_adaptor;
        $data['reg_kode_ont'] = $request->kode_ont;
        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
        $data['reg_harga'] = $request->reg_harga;
        $data['reg_ppn'] = $request->reg_ppn;
        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
        $data['reg_kode_unik'] = $request->reg_kode_unik;
        $data['reg_dana_kas'] = $request->reg_dana_kas;
        $data['reg_catatan'] = $request->reg_catatan;
        $data['reg_profile'] = $request->reg_profile;
        $data['reg_status'] = '0';
        $data['reg_progres'] = '0';
        $update['input_maps'] =  $request->maps;
        $update['input_status'] =  '1';
        $update_barang['subbarang_status'] =  '1';
        $update_barang['subbarang_keluar'] = '1';
        $update_barang['subbarang_keterangan'] = 'PSB ' . $request->reg_nama;


        Registrasi::create($data);
        InputData::where('id', $request->reg_idpel)->update($update);
        SubBarang::where('id_subbarang', $request->reg_kode_pactcore)->update($update_barang);
        SubBarang::where('id_subbarang', $request->kode_adaptor)->update($update_barang);
        SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
        return redirect()->route('admin.reg.registrasi_api', ['id' => $data['reg_idpel']]);
    }

    public function pilih_pelanggan_registrasi($id)
    {
        $data['tampil_data'] =  InputData::whereId($id)->first();

        $date = date('Ym');
        $tgl = date('d');
        $th = substr($date, 2);
        $data['nolay'] = $th . $id . $tgl;


        $hilangspasi = preg_replace('/\s+/', '_', $data['tampil_data']->input_nama);
        $namalayanan = strtoupper($hilangspasi);
        $data['username'] =  $data['nolay'] . '@' . $namalayanan;



        return response()->json($data);
    }
    public function getPaket(Request $request, $id)
    {
        $data['data_biaya'] = SettingBiaya::first();
        $data['data_paket'] = Paket::where("paket_id", $id)->get();
        return response()->json($data);
    }
    public function validasi_pachcore(Request $request, $id)
    {
        $kode_pact = SubBarang::where("id_subbarang", $id)->where("subbarang_status", '0')
            ->where("subbarang_ktg", 'PACTCORE')->first();

        return response()->json($kode_pact);
    }
    public function validasi_adaptor(Request $request, $id)
    {
        $kode_adp = SubBarang::where("id_subbarang", $id)->where("subbarang_status", '0')
            ->where("subbarang_ktg", 'ADAPTOR')->first();

        return response()->json($kode_adp);
    }
    public function validasi_ont(Request $request, $id)
    {
        $kode_ont = SubBarang::where("id_subbarang", $id)->where("subbarang_status", '0')
            ->where("subbarang_ktg", 'ONT')->first();

        return response()->json($kode_ont);
    }
    public function berita_acara($id)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        // dd($data['profile_perusahaan']->app_logo);
        $data['nama_admin'] = Auth::user()->name;
        $data['berita_acara'] =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_idpel', $id)
            ->first();



        $data['seles'] = User::whereId($data['berita_acara']->input_sales)->first();
        $nama = InputData::where('id', $id)->first();
        if ($nama) {
            $sales = $nama->input_nama;
        } else {
            $sales = '-';
        }
        $pdf = App::make('dompdf.wrapper');
        $html = view('PSB/berita_acara', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download('Berita_Acara_' . $sales . '.pdf');
    }
    public function registrasi_import(Request $request)
    {
        Excel::import(new RegistrasiImport(), $request->file('file'));

        // $data['tgl_sekarang'] = Carbon::now()->toDateString();
        // $data['tgl_jatuh_tempo'] =  Carbon::create('2024-04-3')->toDateString();

        $q = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_progres', 'MIGRASI')
            ->get();

        $swaktu = SettingWaktuTagihan::first();
        foreach ($q as $query) {
            // if (Carbon::now()->toDateString() > Carbon::create($query->tgl_jatuh_tempo)->toDateString()) {
            $periode1blan = Carbon::create($query->reg_tgl_jatuh_tempo)->toDateString() . ' - ' . Carbon::create($query->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString();
            $inv_tgl_isolir1blan = Carbon::create($query->reg_tgl_jatuh_tempo)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

            echo  $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik  + $query->reg_ppn . '<br>';
            $invoice_id = rand(10000, 19999);
            // Invoice::create([
            //     'inv_id' => $invoice_id,
            //     'inv_status' => 'UNPAID',
            //     'inv_idpel' => $query->reg_idpel,
            //     'inv_nolayanan' => $query->reg_nolayanan,
            //     'inv_nama' => $query->input_nama,
            //     'inv_jenis_tagihan' => $query->reg_jenis_tagihan,
            //     'inv_profile' => $query->paket_nama,
            //     'inv_mitra' => 'SYSTEM',
            //     'inv_kategori' => 'OTOMATIS',
            //     'inv_diskon' => '0',
            //     'inv_note' => $query->input_nama,
            //     'inv_tgl_isolir' => $inv_tgl_isolir1blan,
            //     'inv_total' =>   $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik  + $query->reg_ppn,
            //     'inv_tgl_tagih' => $query->reg_tgl_tagih,
            //     'inv_tgl_jatuh_tempo' => $query->reg_tgl_jatuh_tempo,
            //     'inv_periode' => $periode1blan,
            // ]);

            // SubInvoice::create([
            //     'subinvoice_id' => $invoice_id,
            //     'subinvoice_harga' => $query->reg_harga,
            //     'subinvoice_ppn' => $query->reg_ppn,
            //     'subinvoice_total' => $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik  + $query->reg_ppn,
            //     'subinvoice_qty' => '1',
            //     'subinvoice_deskripsi' => $query->paket_nama . ' ( ' . $periode1blan . ' )',
            //     'subinvoice_status' => '0',
            // ]);


            // dd('invoice');
            // } else {
            //     $data['waktu_jttempo'] = "belum jatuh tempo";
            //     dd($data);
            // }
        }
        // $update['reg_progres'] = '1';
        // Registrasi::update($update);


        dd('tembus');
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }
}
