<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Imports\Import\RegistrasiImport;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Barang\Barang;
use App\Models\Barang\SubBarang;
use App\Models\Barang\supplier;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Teknisi\Teknisi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Operasional;
use App\Models\Transaksi\SubInvoice;
use App\Models\User;
use App\Models\Pesan\Pesan;
use App\Models\PSB\PutusBerlanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\NOC\NocController;
use App\Models\Aplikasi\Data_Site;
use App\Models\Mitra\MutasiSales;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Registrasi\Data_Deaktivasi;
use App\Models\Teknisi\Data_Olt;
use App\Models\Teknisi\Data_pop;
use Illuminate\Support\Facades\Storage;
use App\Models\Tiket\Data_Tiket;

class RegistrasiController extends Controller
{
    public function index()
    {
        
        $data['input_data'] = InputData::where('input_status', 'INPUT DATA')->get();
        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        $data['data_pop'] = Data_pop::where('pop_status', 'Enable')->get();
        $data['data_paket'] = Paket::where('paket_status', 'Enable')->get();
        $data['data_router'] = Router::where('router_status', 'Enable')->get();
        $data_biaya = SettingBiaya::first();
        if ($data_biaya) {
            $data['data_biaya'] = $data_biaya;
        } else {
            $notifikasi = array(
                'pesan' => 'Pengaturan data biaya, belum diatur. Silahkan atur terlebih dahulu. Setting- Applikasi - Biaya',
                'alert' => 'warning',
            );
            return redirect()->route('admin.psb.index')->with($notifikasi);
        }
        return view('Registrasi/registrasi', $data);
    }

    public function store(Request $request)
    {
        $sbiaya = SettingBiaya::first();


        $user = (new GlobalController)->user_admin();
        $admin = $user['user_nama'];
        $cek_sales = (new GlobalController)->role($request->reg_sales);
        // dd($cek_sales->mts_komisi_sales);
        $no_tiket = (new GlobalController)->nomor_tiket();
        $site = Router::join('data_pops', 'data_pops.pop_id', '=', 'routers.router_id_pop')
            ->where('routers.id', $request->reg_router)->first();



        Session::flash('reg_nama', $request->reg_nama); #
        Session::flash('reg_idpel', $request->reg_idpel); #
        Session::flash('reg_nolayanan', $request->reg_nolayanan); #
        Session::flash('reg_hp', $request->reg_hp); #
        Session::flash('reg_hp2', $request->reg_hp2); #
        Session::flash('reg_alamat_pasang', $request->reg_alamat_pasang); #
        Session::flash('reg_maps', $request->reg_maps); #
        Session::flash('reg_sales', $request->reg_sales); #
        Session::flash('reg_sales_nama', $request->reg_sales_nama); #
        Session::flash('reg_site', $request->reg_site); #
        Session::flash('reg_pop', $request->reg_pop); #
        Session::flash('reg_router', $request->reg_router); #
        Session::flash('reg_layanan', $request->reg_layanan); #
        Session::flash('reg_ip_address', $request->reg_ip_address); #
        Session::flash('reg_username', $request->reg_username); #
        Session::flash('reg_tgl', $request->reg_tgl); #
        Session::flash('reg_profile', $request->reg_profile); #
        Session::flash('reg_jenis_tagihan', $request->reg_jenis_tagihan); #
        Session::flash('reg_harga', $request->reg_harga); #
        Session::flash('reg_ppn', $request->reg_ppn); #
        Session::flash('input_subseles', $request->input_subseles); #
        Session::flash('reg_dana_kerjasama', $request->reg_dana_kerjasama); #
        Session::flash('reg_kode_unik', $request->reg_kode_unik); #
        Session::flash('reg_dana_kas', $request->reg_dana_kas); #
        Session::flash('reg_catatan', $request->reg_catatan);
        Session::flash('reg_inv_control', $request->reg_inv_control); #
        Session::flash('reg_tgl_pasang', $request->reg_tgl_pasang); #
        if ($request->reg_profile) {
            $paket_nama = Paket::where('paket_id', $request->reg_profile)->first();
            Session::flash('paket_nama', $paket_nama->paket_nama);
        }
        // dd($paket_nama);

        $request->validate([
            'reg_nama' => 'required',
            'reg_idpel' => 'unique:registrasis,reg_idpel',
            'reg_nolayanan' => 'unique:registrasis,reg_nolayanan',
            'reg_hp' => 'required',
            'reg_hp2' => 'required',
            'reg_alamat_pasang' => 'required',
            'reg_maps' => 'required',
            'reg_sales' => 'required',
            'reg_site' => 'required',
            'reg_pop' => 'required',
            'reg_router' => 'required',
            'reg_layanan' => 'required',
            'reg_username' => 'required',
            'reg_jenis_tagihan' => 'required',
            'reg_harga' => 'required',
            'input_subseles' => 'required',
            'reg_profile' => 'required',
            'reg_inv_control' => 'required',
            'reg_tgl_pasang' => 'required',
        ], [
            'reg_nama.required' => 'Nama tidak boleh kosong',
            'reg_idpel.unique' => 'Id Pelanggan sudah ada, Hapus input data terlebih dahulu',
            'reg_nolayanan.unique' => 'No layanan sudah ada, Ulangi Kembali',
            'reg_hp.required' => 'No Whatsapp 1 tidak boleh kosong',
            'reg_hp2.required' => 'No Whatsapp 2 tidak boleh kosong',
            'reg_alamat_pasang.required' => 'Alamat tidak boleh kosong',
            'reg_maps.required' => 'Maps tidak boleh kosong',
            'reg_sales.required' => 'Sales tidak boleh kosong',
            'reg_site.required' => 'Site tidak boleh kosong',
            'reg_layanan.required' => 'Layanan tidak boleh kosong',
            'reg_router.required' => 'Router tidak boleh kosong',
            'reg_username.required' => 'Username tidak boleh kosong',
            'reg_jenis_tagihan.required' => 'Jenis Tagihan tidak boleh kosong',
            'reg_harga.required' => 'Harga tidak boleh kosong',
            'input_subseles.required' => 'Sub Sales tidak boleh kosong',
            'reg_pop.required' => 'POP tidak boleh kosong',
            'reg_profile.required' => 'Paket tidak boleh kosong',
            'reg_inv_control.required' => 'Invoice Control tidak boleh kosong',
            'reg_tgl_pasang.required' => 'Tanggal pemasangan tidak boleh kosong',
        ]);


        $dates = Carbon::now()->toDateTimeString();
        $tanggal = Carbon::now()->toDateString();
        // $tgl_aktif = date('d/m/Y', strtotime($dates));
        $tgl_pasang = Carbon::create($tanggal)->addDay(1)->toDateString();

        if ($cek_sales->role_id == 10 && $cek_sales->role_id == 13) {
            if ($request->reg_jenis_tagihan == 'FREE') {
                $data['reg_fee'] = 0;
            } else {
                    $data['reg_fee'] = $cek_sales->mts_komisi_sales;
            }
        } else {
            $data['reg_fee'] = 0;
        }

        $data['reg_idpel'] = $request->reg_idpel;
        $data['reg_sales'] = $request->reg_sales;
        $data['reg_nolayanan'] = $request->reg_nolayanan;
        $data['reg_layanan'] = $request->reg_layanan;
        $data['reg_router'] = $request->reg_router;
        $data['reg_ip_address'] = $request->reg_ip_address;
        $data['reg_username'] = $request->reg_username;
        $data['reg_password'] = $request->reg_password;
        $data['reg_site'] = $request->reg_site;
        $data['reg_pop'] = $request->reg_pop;
        $data['reg_skb'] = $no_tiket;
        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
        $data['reg_harga'] = $request->reg_harga;
        $data['reg_ppn'] = $request->reg_ppn;
        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
        $data['reg_kode_unik'] = $request->reg_kode_unik;
        $data['reg_dana_kas'] = $request->reg_dana_kas;
        $data['reg_catatan'] = $request->reg_catatan;
        $data['reg_profile'] = $request->reg_profile;
        $data['reg_inv_control'] = $request->reg_inv_control;
        $data['reg_status'] = '0';
        $data['reg_progres'] = '0';
        $update['input_maps'] =  $request->reg_maps;
        $update['input_status'] =  'REGIST';

        $tiket['tiket_id'] = $no_tiket;
        $tiket['tiket_pembuat'] = $user['user_id'];
        $tiket['tiket_kode'] = 'T-' . $no_tiket;
        $tiket['tiket_idpel'] = $request->reg_idpel;
        $tiket['tiket_jenis'] = 'Instalasi';
        $tiket['tiket_type'] = 'General';
        $tiket['tiket_site'] = $site->pop_id_site;
        $tiket['tiket_nama'] = 'Instalasi PSB';
        // $tiket['tiket_jadwal_kunjungan'] = date('Y-m-d', strtotime(Carbon::now()));
        $tiket['tiket_keterangan'] = 'Instalasi PSB';
        $tiket['tiket_status'] = 'NEW';



        $status = (new GlobalController)->whatsapp_status();
        if ($status) {
            if ($status->wa_status == 'Enable') {
                $status_pesan = '0';
            } else {
                $status_pesan = '10';
            }


            $pesan_pelanggan['layanan'] = 'CS';
            $pesan_pelanggan['pesan_id_site'] = $request->reg_site;
            $pesan_pelanggan['ket'] = 'registrasi';
            $pesan_pelanggan['target'] = $request->reg_hp;
            $pesan_pelanggan['nama'] = $request->reg_nama;
            $pesan_pelanggan['status'] = $status_pesan;
            $pesan_pelanggan['pesan'] = 'Pelanggan Yth, 
Registrasi layanan internet berhasil, berikut data yang sudah terdaftar di sistem kami :

No.Layanan : *' . $request->reg_nolayanan . '*
Nama : *' . $request->reg_nama . '*
Alamat pasang : ' . $request->reg_alamat_pasang . '
Paket : *' . $paket_nama->paket_nama . '*
Jenis tagihan : ' . $request->reg_jenis_tagihan . '
Biaya tagihan : ' . $request->reg_harga + $request->reg_ppn + $request->reg_dana_kerjasama + $request->reg_kode_unik + $request->reg_dana_kas . '
Tanggal Pasang : ' . date('d-m-Y', strtotime($request->reg_tgl_pasang)) . '

Untuk melihat detail layanan dan pembayaran tagihan bisa melalui client area *'.env('LINK_APK').'*

--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*'.Session::get('app_brand').'*
';


            $pesan_group['layanan'] = 'CS';
            $pesan_group['pesan_id_site'] = $request->reg_site;
            $pesan_group['ket'] = 'registrasi';
            $pesan_group['target'] = env('GROUP_REGISTRASI');;
            $pesan_group['nama'] = $request->reg_nama;
            $pesan_group['status'] = $status_pesan;
            $pesan_group['pesan'] = '               -- LIST PEMASANGAN --

Antrian pemasangan tanggal ' . date('d-m-Y', strtotime($tgl_pasang)) . ' 

No.Layanan : *' . $request->reg_nolayanan . '*
Nama : ' . $request->reg_nama . '
Alamat : ' . $request->reg_alamat_pasang .
                '
Paket : *' . $paket_nama->paket_nama . '*
Jenis tagihan : ' . $request->reg_jenis_tagihan . '
Biaya tagihan : ' . $request->reg_harga + $request->reg_ppn + $request->reg_dana_kerjasama + $request->reg_kode_unik + $request->reg_dana_kas . '
Tanggal Pasang : ' . date('d-m-Y', strtotime($request->reg_tgl_pasang)) . ' 

Diregistrasi Oleh : *' . $admin . '*
';


            Pesan::create([
                'layanan' =>  'NOC',
                'pesan_id_site' =>  $request->reg_site,
                'ket' =>  'tiket',
                'target' =>  env('GROUP_TEKNISI'),
                'status' =>  $status_pesan,
                'nama' =>  'GROUP TEKNISI',
                'pesan' => '               -- TIKET INSTALASI --
No. Tiket : *' . $no_tiket . '*
Kegiatan : Insatalasi PSB
Keterangan : *Insatalasi PSB*

No. Layanan : ' . $request->reg_nolayanan . '
Pelanggan : ' . $request->reg_nama . '
Alamat : ' . $request->reg_alamat_pasang . '
Whatsapp : 0' . $request->reg_hp . '
Whatsapp Alternatif: 0' . $request->reg_hp2 . '
Tanggal tiket : ' . date('Y-m-d h:i:s', strtotime(Carbon::now())) . '
'
            ]);
            Pesan::create($pesan_pelanggan);
            Pesan::create($pesan_group);
        }



        Data_Tiket::create($tiket);
        Registrasi::create($data);
        InputData::where('id', $request->reg_idpel)->update($update);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan pelanggan',
            'alert' => 'success',
        );
        return redirect()->route('admin.psb.index')->with($notifikasi);
    }

    public function delete_registrasi($id)
    {

        $admin = (new GlobalController)->user_admin()['user_id'];
        $data_pelanggan = Registrasi::join('routers', 'routers.id', '=', 'registrasis.reg_router')->where('reg_idpel', $id)->first();
        $update['input_status'] =  'PENDING';
        InputData::where('id', $data_pelanggan->reg_idpel)->update($update);
        $data = Registrasi::where('reg_idpel', $id);
        if ($data) {
            $data->delete();
        }

        $data_tiket = Data_Tiket::where('tiket_idpel', $id)->where('tiket_jenis', 'Instalasi');
        if ($data_tiket) {
            $data_tiket->delete();
        }
        $notifikasi = array(
            'pesan' => 'Hapus Data Registrasi Berhasil berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.psb.index', ['id' => $id])->with($notifikasi);
    }

    public function pilih_pelanggan_registrasi($id)
    {
        // return response()->json($id);
        $data['tampil_data'] =  InputData::select('input_data.*', 'users.id as user_id', 'users.name as user_nama')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('input_data.id', $id)->first();

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

    public function print_berita_acara($id)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        $data['berita_acara'] =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        $seles = User::whereId($data['berita_acara']->input_sales)->first();
        if ($seles) {
            $data['seles'] = $seles->name;
        } else {
            $data['seles'] = '-';
        }

        // dd($data);
        $nama = InputData::where('id', $id)->first();
        if ($nama) {
            $sales = $nama->input_nama;
        } else {
            $sales = '-';
        }
        // $pdf = App::make('dompdf.wrapper');
        // $html = view('PSB/print_berita_acara', $data)->render();
        // $pdf->loadHTML($html);
        // $pdf->setPaper('A4', 'potraid');
        // return $pdf->download('Berita_Acara_' . $sales . '.pdf');
        return view('PSB/print_berita_acara', $data);
    }

    public function berita_acara()
    {

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->orderBy('tgl', 'DESC');

        $data['data_registrasi'] = $query->get();

        return view('PSB/berita_acara', $data);
    }
   
    // public function konfirm_pencairan(Request $request)
    // {
    //     $admin = Auth::user()->id;
    //     $nama_admin = Auth::user()->name;
    //     $biaya = SettingBiaya::first();
    //     $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

    //     Teknisi::whereIn('teknisi_idpel', $request->idpel)->where('teknisi_status', '1')->where('teknisi_job', 'PSB')->update(
    //         [
    //             'teknisi_keuangan_userid' => $admin,
    //             'teknisi_status' => 2,
    //         ]
    //     );
    //     $count = count($request->idpel);
    //     $total = ($biaya->biaya_psb + $biaya->biaya_sales) * $count;
    //     $psb = $biaya->biaya_psb * $count;
    //     $marketing = $biaya->biaya_sales * $count;

    //     $cek_saldo = (new GlobalController)->mutasi_jurnal();

    //     if ($cek_saldo['saldo'] >= $total) {
    //         Jurnal::create([
    //             'jurnal_id' => time(),
    //             'jurnal_tgl' => $data['input_tgl'],
    //             'jurnal_uraian' => 'Pencairan PSB oleh ' . $nama_admin . ' Sebanyak ' . $count . ' Pelanggan',
    //             'jurnal_kategori' => 'PENGELUARAN',
    //             'jurnal_keterangan' => 'PSB',
    //             'jurnal_admin' => $admin,
    //             'jurnal_penerima' => $request->penerima,
    //             'jurnal_metode_bayar' => $request->akun,
    //             'jurnal_debet' => $psb,
    //             'jurnal_status' => 1,
    //         ]);
    //         Jurnal::create([
    //             'jurnal_id' => time(),
    //             'jurnal_tgl' => $data['input_tgl'],
    //             'jurnal_uraian' => 'Pencairan MARKETING oleh ' . $nama_admin . ' Sebanyak ' . $count . ' Pelanggan',
    //             'jurnal_kategori' => 'PENGELUARAN',
    //             'jurnal_keterangan' => 'MARKETING',
    //             'jurnal_admin' => $admin,
    //             'jurnal_penerima' => $request->penerima,
    //             'jurnal_metode_bayar' => $request->akun,
    //             'jurnal_debet' => $marketing,
    //             'jurnal_status' => 1,
    //         ]);

    //         Registrasi::where('reg_progres', '4')->whereIn('reg_idpel', $request->idpel)->update(['reg_progres' => '5']);

    //         $notifikasi = 'berhasil';
    //         return response()->json($notifikasi);
    //     } else {
    //         $notifikasi = 'saldo_tidak_cukup';
    //         return response()->json($notifikasi);
    //     }
    // }

    public function bukti_kas_keluar($id)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['biaya_sales'] = SettingBiaya::first();
        // dd($data['biaya_sales']['biaya_sales_continue']);
        $user = (new GlobalController)->user_admin();
        $data['nama_admin'] = $user['user_nama'];
        $data['id_admin'] = $user['user_id'];
        // $teknisi = Teknisi::where('teknisi_idpel', $id)->where('teknisis.teknisi_job', 'PSB')->where('teknisis.teknisi_psb', '>', '0')->first();
        // if ($teknisi) {
        $data['kas'] =  Registrasi::select('registrasis.*', 'input_data.*', 'teknisis.*', 'pakets.*',)
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'registrasis.reg_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->join('data__barang_keluars', 'data__barang_keluars.bk_id', '=', 'registrasis.reg_skb')
            ->where('registrasis.reg_idpel', $id)
            ->where('teknisis.teknisi_job', 'PSB')
            // ->where('teknisis.teknisi_psb', '>', '0') #Ujicoba Update 16-05
            ->where('teknisis.teknisi_status','1')
            ->first();

        $query = Data_BarangKeluar::select('data__barangs.*', 'data__barang_keluars.*')
            ->join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC')
            ->where('bk_idpel', $id);
        $data['print_skb'] = $query->get();
        $query1 = Data_BarangKeluar::where('bk_idpel', $id);
        $data['data'] = $query1->first();
        $data['total'] = $query1->sum('bk_harga');

        // if ($data['kas']->reg_fee > 0) {
        //     $saldo = (new globalController)->total_mutasi_sales($data['id_admin']);
        //     $total = $saldo + $data['biaya_sales']['biaya_sales_continue']; #SALDO MUTASI = DEBET - KREDIT

        //     $mutasi_sales['smt_user_id'] = $data['kas']->input_sales;
        //     $mutasi_sales['smt_admin'] = $data['id_admin'];
        //     $mutasi_sales['smt_kategori'] = 'PENDAPATAN';
        //     $mutasi_sales['smt_deskripsi'] = $data['kas']->input_nama;
        //     $mutasi_sales['smt_cabar'] = '2';
        //     $mutasi_sales['smt_kredit'] = $data['biaya_sales']['biaya_sales_continue'];
        //     $mutasi_sales['smt_debet'] = 0;
        //     $mutasi_sales['smt_saldo'] = $total;
        //     $mutasi_sales['smt_biaya_adm'] = 0;
        //     MutasiSales::create($mutasi_sales);
        // }

        // dd($mutasi_sales);

        $update['reg_progres'] = '4';
        Registrasi::where('reg_idpel', $id)->update($update);

        $data['berita_acara'] =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        $data['noc'] = User::whereId($data['kas']->teknisi_noc_userid)->first();


        if ($data['kas']->input_sales) {
            $data['seles'] = User::whereId($data['kas']->input_sales)->first();
        } else {
            dd('jonk');
        }


        $nama = InputData::where('id', $id)->first();
        if ($nama) {
            $sales = $nama->input_nama;
        } else {
            $sales = '-';
        }
        $pdf = App::make('dompdf.wrapper');
        $html = view('PSB/bukti_kas_keluar', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download('kas_' . $sales . '.pdf');
    }



    public function registrasi_import(Request $request)
    {
        Excel::import(new RegistrasiImport(), $request->file('file'));


        $q = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_progres', 'MIGRASI')
            ->get();

        $swaktu = SettingWaktuTagihan::first();
        if ($swaktu->wt_jeda_isolir_hari = '0') {
            $jeda = '0';
        } else {
            $jeda = $swaktu->wt_jeda_isolir_hari;
        }
        foreach ($q as $query) {
            // if (Carbon::now()->toDateString() > Carbon::create($query->tgl_jatuh_tempo)->toDateString()) {
            $periode1blan = Carbon::create($query->reg_tgl_jatuh_tempo)->toDateString() . ' - ' . Carbon::create($query->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString();
            $inv_tgl_isolir1blan = Carbon::create($query->reg_tgl_jatuh_tempo)->addDay($jeda)->toDateString();
            $invoice_id = rand(10000, 19999);
            Invoice::create([
                'inv_id' => $invoice_id,
                'inv_status' => 'UNPAID',
                'inv_idpel' => $query->reg_idpel,
                'inv_nolayanan' => $query->reg_nolayanan,
                'inv_nama' => $query->input_nama,
                'inv_jenis_tagihan' => $query->reg_jenis_tagihan,
                'inv_profile' => $query->paket_nama,
                'inv_mitra' => 'SYSTEM',
                'inv_kategori' => 'OTOMATIS',
                'inv_diskon' => '0',
                'inv_note' => $query->input_nama,
                'inv_tgl_isolir' => $inv_tgl_isolir1blan,
                'inv_total' =>   $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik  + $query->reg_ppn,
                'inv_tgl_tagih' => $query->reg_tgl_tagih,
                'inv_tgl_jatuh_tempo' => $query->reg_tgl_jatuh_tempo,
                'inv_periode' => $periode1blan,
            ]);

            SubInvoice::create([
                'subinvoice_id' => $invoice_id,
                'subinvoice_harga' => $query->reg_harga,
                'subinvoice_ppn' => $query->reg_ppn,
                'subinvoice_total' => $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik  + $query->reg_ppn,
                'subinvoice_qty' => '1',
                'subinvoice_deskripsi' => $query->paket_nama . ' ( ' . $periode1blan . ' )',
                'subinvoice_status' => '0',
            ]);
        }



        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.index')->with($notifikasi);
    }


    public function form_update_pelanggan($id)
    {

        $user = (new globalController)->user_admin();
        $data['user_nama'] = $user['user_nama'];
        $data['user_id'] = $user['user_id'];
        $data['user'] = User::get();

        $data['tgl_akhir'] = date('t', strtotime(Carbon::now()));
        $status_inet = (new NocController)->status_inet($id);
        $data['input_data'] = InputData::all();
        $data['data_router'] = Router::where('router_status','Enable')->get();
        // dd($data['data_router']);
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();
        $data['data_teknisi'] = (new GlobalController)->getTeknisi();

        $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('data__sites', 'data__sites.site_id', '=', 'registrasis.reg_site')
            ->join('data_pops', 'data_pops.pop_id', '=', 'registrasis.reg_pop')
            ->where('input_data.id', $id)
            ->first();
        $data['router'] = Router::join('data_pops', 'data_pops.pop_id', '=', 'routers.router_id_pop')
            ->get();
        $data['data_olt'] = Data_pop::join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
            ->where('pop_id', $data['data']->reg_pop)->get();

        $query = Data_BarangKeluar::join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->orderBy('data__barang_keluars.bk_waktu_keluar', 'ASC')
            ->where('bk_idpel', $data['data']->reg_idpel);
        $data['print_skb'] = $query->get();


        $data['status'] = $status_inet['status'];
        $data['uptime'] = $status_inet['uptime'];
        $data['address'] = $status_inet['address'];
        $data['status_secret'] = $status_inet['status_secret'];
        return view('Registrasi/form_update_pelanggan', $data);
    }

    public function proses_update_noskb(Request $request, $id) ##Update No SKB Pada Form Update data pelanggan
    {
        Session::flash('reg_skb', $request->reg_skb);
        $request->validate([
            'reg_skb' => 'required',
        ], [
            'reg_skb.required' => 'Nomor SKB tidak boleh kosong',
        ]);
        $data['reg_skb'] = $request->reg_skb;
        Registrasi::where('reg_idpel', $id)->update($data);
        $notifikasi = array(
            'pesan' => 'Update Nomor SKB Berhasil ',
            'alert' => 'success',
        );
        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
    }
    public function proses_aktivasi_pelanggan(Request $request, $id)
    {
        Session::flash('reg_site', $request->reg_site);
        Session::flash('reg_pop', $request->reg_pop);
        Session::flash('reg_router', $request->reg_router);
        Session::flash('reg_olt', $request->reg_olt);
        Session::flash('reg_odc', $request->reg_odc);
        Session::flash('reg_odp', $request->reg_odp);
        Session::flash('reg_in_ont', $request->reg_in_ont);
        Session::flash('reg_onuid', $request->reg_onuid);
        Session::flash('reg_slot_odp', $request->reg_slot_odp);
        Session::flash('reg_terpakai', $request->reg_terpakai);
        Session::flash('teknisi1', $request->teknisi1);
        Session::flash('teknisi2', $request->teknisi2);
        Session::flash('input_koordinat', $request->input_koordinat);
        Session::flash('reg_koordinat_odp', $request->reg_koordinat_odp);
        // Session::flash('reg_img', $request->reg_koordinat_odp);
        Session::flash('reg_foto_odp', $request->reg_koordinat_odp);


        $request->validate([
            'reg_site' => 'required',
            'reg_pop' => 'required',
            'reg_router' => 'required',
            'reg_olt' => 'required',
            'reg_odc' => 'required',
            'reg_odp' => 'required',
            'reg_in_ont' => 'required',
            'reg_onuid' => 'required',
            'reg_slot_odp' => 'required',
            'teknisi1' => 'required',
            'teknisi2' => 'required',
            'input_koordinat' => 'required',
            'reg_koordinat_odp' => 'required',
            'reg_img' => 'required|max:2000|mimes:jpg',
            'reg_foto_odp' => 'required|max:2000|mimes:jpg',
        ], [
            'reg_site.required' => 'Site tidak boleh kosong',
            'reg_pop.required' => 'POP tidak boleh kosong',
            'reg_router.required' => 'Router tidak boleh kosong',
            'reg_olt.required' => 'OLT tidak boleh kosong',
            'reg_odc.required' => 'ODC tidak boleh kosong',
            'reg_odp.required' => 'ODP tidak boleh kosong',
            'reg_in_ont.required' => 'Redaman tidak boleh kosong',
            'reg_onuid.required' => 'Onu Id tidak boleh kosong',
            'reg_slot_odp.required' => 'Slot Odp tidak boleh kosong',
            'teknisi1.required' => 'Teknisi 1 tidak boleh kosong',
            'teknisi2.required' => 'Teknisi 2 tidak boleh kosong',
            'input_koordinat.required' => 'Koordinat Rumah pelanggan tidak boleh kosong',
            'reg_koordinat_odp.required' => 'Koordinat ODP tidak boleh kosong',
            'reg_img.required' => 'Foto rumah tidak boleh kosong',
            'reg_img.max' => 'Ukuran foto terlalu besar',
            'reg_img.mimes' => 'Format hanya bisa jpg',
            'reg_foto_odp.required' => 'Foto Odp tidak boleh kosong',
            'reg_foto_odp.max' => 'Ukuran foto terlalu besar',
            'reg_foto_odp.mimes' => 'Format hanya bisa jpg',
        ]);



        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();

        if ($query->reg_layanan == 'PPP') {
            $API = (new ApiController)->aktivasi_psb_ppp($query);
            if ($API == 0) {
                //LANJUTAN AKTIVASI
                (new AktivasiController)->aktivasi_psb($request, $query, $id);
                $notifikasi = array(
                    'pesan' => 'Aktivasi Berhasil ',
                    'alert' => 'success',
                );
                return redirect()->route('admin.reg.data_aktivasi_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 1) {
                $notifikasi = array(
                    'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 2) {
                $notifikasi = array(
                    'pesan' => 'Router Discconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            $API = (new ApiController)->aktivasi_psb_hotspot($query);

            if ($API == 0) {
                //LANJUTAN AKTIVASI
                (new AktivasiController)->aktivasi_psb($request, $query, $id);
                $notifikasi = array(
                    'pesan' => 'Aktivasi Berhasil ',
                    'alert' => 'success',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 1) {
                $notifikasi = array(
                    'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 2) {
                $notifikasi = array(
                    'pesan' => 'Router Discconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        }
    }
    public function data_aktivasi_pelanggan()
    {
        $month = Carbon::now()->addMonth(-0)->format('m');
        $bulan_lalu = Carbon::now()->addMonth(-1)->format('m');
        $d = date('d');

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('reg_progres', '<=', 2)
            ->orderBy('tgl', 'DESC');

        $data['data_registrasi'] = $query->get(10);

        $data['count_inputdata'] = InputData::count();
        $data['count_aktivasi'] = $query->count();
        $data['count_teraktivasi'] = Registrasi::where('reg_progres', '>=', '3')->whereDate('created_at', '=', $d)->count();

        return view('Registrasi/data_aktivasi', $data);
    }
    public function aktivasi_pelanggan($id)
    {

        $cek_tiket = Data_Tiket::where('tiket_idpel', $id)->where('tiket_status', 'NEW')->count();
        // dd( $cek_tiket);
        if ($cek_tiket == 0) {
            $data['tgl_akhir'] = date('t', strtotime(Carbon::now()));
            // dd($data['tgl_akhir']);
            $status_inet = (new NocController)->status_inet($id);
            if($status_inet['status'] == 'TIDAK TERSAMBUNG KE SERVER'){
                 $notifikasi = [
                    'pesan' => 'Router Disconnected. Perikasi koneksi router.',
                    'alert' => 'warning',
                ];
                return redirect()->route('admin.reg.data_aktivasi_pelanggan')->with($notifikasi);
            } else {
                $data['input_data'] = InputData::all();
                $data['data_router'] = Router::all();
                $data['data_paket'] = Paket::all();
                $data['data_biaya'] = SettingBiaya::first();
                $data['data_teknisi'] = (new GlobalController)->getTeknisi();

                $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
                    ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                    ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
                    ->join('data__sites', 'data__sites.site_id', '=', 'registrasis.reg_site')
                    ->join('data_pops', 'data_pops.pop_id', '=', 'registrasis.reg_pop')
                    ->where('input_data.id', $id)
                    ->first();

                $data['router'] = Router::join('data_pops', 'data_pops.pop_id', '=', 'routers.router_id_pop')
                    ->get();

                $data['data_olt'] = Data_pop::join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
                    ->where('pop_id', $data['data']->reg_pop)->get();
                $data['status'] = $status_inet['status'];
                $data['uptime'] = $status_inet['uptime'];
                $data['address'] = $status_inet['address'];
                $data['status_secret'] = $status_inet['status_secret'];
                return view('Registrasi/form_aktivasi', $data);
            }
            } else {
                $notifikasi = [
                    'pesan' => 'Tiket instalasi belum di Closed, Silahkan Closed Tiket terlebih dahulu',
                    'alert' => 'warning',
                ];
                return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
            }
            // dd(;
    }


    public function deaktivasi_pelanggan(Request $request, $id)
    {
        $nama_admin = Auth::user()->name;
        $tgl = date('Y-m-d H:m:s', strtotime(carbon::now()));
        $tgl_ambil_perangkat = date('Y-m-d', strtotime($request->deaktivasi_tanggal_pengambilan));

        $query =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('registrasis.reg_idpel', $id)->first();

        if ($request->status == 'PUTUS LANGGANAN') {
            $keterangan = 'PUTUS BERLANGGANAN - ' . strtoupper($query->input_nama);
            $progres = '90';
        } else {
            $keterangan = 'PUTUS SEMENTARA  - ' . strtoupper($query->input_nama);
            $progres = '100';
        }


        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;


        if ($API->connect($ip, $user, $pass)) {
            $cek_status = $API->comm('/ppp/active/print', [
                '?name' => $query->reg_username,
            ]);
            if ($cek_status) {
                $API->comm('/ppp/active/remove', [
                    '.id' => $cek_status[0]['.id'],
                ]);
            }
            $cari_pel = $API->comm('/ppp/secret/print', [
                '?name' => $query->reg_username,
            ]);
            if ($cari_pel) {
                $API->comm('/ppp/secret/remove', [
                    '.id' =>  $cari_pel['0']['.id']
                ]);
            }

            $data = Invoice::where('inv_idpel', $id)->where('inv_status', '!=', 'PAID')->first();
            if ($data) {
                $data->delete();
                SubInvoice::where('subinvoice_id', $data->inv_id)->delete();
            }

            Registrasi::where('reg_idpel', $id)->update([
                'reg_progres' => $progres,
                'reg_catatan' => $request->reg_catatan,
                'reg_tgl_deaktivasi' => $tgl_ambil_perangkat,
            ]);
            Data_Deaktivasi::create([
                'deaktivasi_idpel' => $id,
                'deaktivasi_mac' => $request->deaktivasi_mac,
                'deaktivasi_sn' => $request->deaktivasi_sn,
                'deaktivasi_kelengkapan_perangkat' => $request->kelengkapan,
                'deaktivasi_tanggal_pengambilan' => $tgl_ambil_perangkat,
                'deaktivasi_pengambil_perangkat' => $request->deaktivasi_pengambil_perangkat,
                'deaktivasi_admin' => $request->deaktivasi_admin,
                'deaktivasi_alasan_deaktivasi' => $request->deaktivasi_alasan_deaktivasi,
                'deaktivasi_pernyataan' => $request->deaktivasi_pernyataan,
                'deaktivasi_admin' =>  $nama_admin,
            ]);

            if ($request->kelengkapan == 'ONT & Adaptor') {
                $kode_barang = [$request->kode_barang_ont, $request->kode_barang_adp];
                Data_BarangKeluar::whereIn('bk_id_barang', $kode_barang)->delete();
                Data_Barang::whereIn('barang_id', $kode_barang)->update([
                    'barang_digunakan' => 0,
                    'barang_dicek' => 1,
                    'barang_ket' => 'Pengambilan Perangkat',
                ]);
            } elseif ($request->kelengkapan == 'ONT') {
                Data_BarangKeluar::where('bk_id_barang', $request->kode_barang_ont)->delete();
                Data_Barang::where('barang_id', $request->kode_barang_ont)->update([
                    'barang_digunakan' => 0,
                    'barang_dicek' => 1,
                    'barang_ket' => 'Pengambilan Perangkat',
                ]);
                Data_Barang::where('barang_id', $request->kode_barang_adp)->update([
                    'barang_digunakan' => 0,
                    'barang_hilang' => 1,
                ]);
            } elseif ($request->kelengkapan == 'Hilang') {
                $kode_barang = [$request->kode_barang_ont, $request->kode_barang_adp];
                Data_Barang::whereIn('barang_id', $kode_barang)->update([
                    'barang_digunakan' => 0,
                    'barang_hilang' => 1,
                ]);
            }

            $notifikasi = [
                'pesan' => 'Berhasil melakukan pemutusan pelanggan',
                'alert' => 'success',
            ];
            return redirect()->route('admin.psb.index')->with($notifikasi);
        } else {
            $notifikasi = [
                'pesan' => 'Gagal melakukan pemutusan pelanggan. Router disconnected',
                'alert' => 'error',
            ];
            return redirect()->route('admin.psb.data_deaktivasi')->with($notifikasi);
        }
    }
    public function data_deaktivasi()
    {
        // $month = Carbon::now()->addMonth(-0)->format('m');
        // $bulan_lalu = Carbon::now()->addMonth(-1)->format('m');
        $m = date('m');
        $user = (new globalController)->user_admin();
        $data['user_nama'] = $user['user_nama'];
        $data['user_id'] = $user['user_id'];
        $data['user'] = User::get();

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('reg_progres', '>=', 90)
            ->where('reg_progres', '<=', 100)
            ->orderBy('tgl', 'DESC');

        $data['data_registrasi'] = $query->get();

        $data['total_deaktivasi'] = $query->count();
        $data['deaktivasi_month'] = Registrasi::where('reg_progres', '>=', 90)
            ->orWhere('reg_progres', '<=', 100)
            ->whereMonth('reg_tgl_deaktivasi', '=', $m)
            ->count();
        $count_perangkat = Data_Deaktivasi::whereMonth('deaktivasi_tanggal_pengambilan', '=', $m);

        $data['ont_hilang'] = $count_perangkat->where('deaktivasi_kelengkapan_perangkat', '=', 'Hilang')->count();
        $data['adaptor_hilang'] = $count_perangkat->where('deaktivasi_kelengkapan_perangkat', '=', 'ONT')->count();

        $data['lengkap'] = Data_Deaktivasi::where('deaktivasi_kelengkapan_perangkat', '=', 'ONT & Adaptor')
            ->whereMonth('deaktivasi_tanggal_pengambilan', '=', $m)->count();

        return view('Registrasi/data_deaktivasi', $data);
    }

    // public function berita_acara_deaktivasi



    public function berita_acara_deaktivasi($id)
    {

        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        $data['berita_acara'] =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        $seles = User::whereId($data['berita_acara']->input_sales)->first();
        if ($seles) {
            $data['seles'] = $seles->name;
        } else {
            $data['seles'] = '-';
        }

        // dd($data);
        $nama = InputData::where('id', $id)->first();
        if ($nama) {
            $sales = $nama->input_nama;
        } else {
            $sales = '-';
        }
        // $pdf = App::make('dompdf.wrapper');
        // $html = view('PSB/print_berita_acara', $data)->render();
        // $pdf->loadHTML($html);
        // $pdf->setPaper('A4', 'potraid');
        // return $pdf->download('Berita_Acara_' . $sales . '.pdf');

        return view('Registrasi/print_berita_acara_deaktivasi', $data);
    }
    public function verifikasi_deaktivasi(Request $request, $id)
    {

        $data = '';

        // 'deaktivasi_idpel',
        // 'deaktivasi_mac',
        // 'deaktivasi_sn',
        // 'deaktivasi_kelengkapan_perangkat',
        // 'deaktivasi_tanggal_pengambilan',
        // 'deaktivasi_pengambil_perangkat',
        // 'deaktivasi_admin',
        // 'deaktivasi_alasan_deaktivasi',


        return view('Registrasi/berita_acara_deaktivasi', $data);
    }

    public function print_list_deaktivasi(Request $request)
    {
        $user = (new GlobalController)->user_admin();
        $data['admin'] = $user['user_nama'];
        $data['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $data['end_date'] = date('Y-m-d', strtotime($request->end_date));
        // dd($data);
        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('data__deaktivasis', 'data__deaktivasis.deaktivasi_idpel', '=', 'registrasis.reg_idpel')
            ->whereDate('data__deaktivasis.created_at', '>=', $data['start_date'])
            ->whereDate('data__deaktivasis.created_at', '<=', $data['end_date'])
            ->orderBy('data__deaktivasis.created_at', 'ASC');

        $data['list_deaktivasi'] = $query->get();
        $pdf = App::make('dompdf.wrapper');
        $html = view('Registrasi/print_list_deaktivasi', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Data Deaktivasi Periode ' . $data['start_date'] . ' - ' . $data['end_date'] . '.pdf');
        // return view('Registrasi/print_list_deaktivasi', $data);
    }
    public function followup()
    {
        $tanggal = date('Y-m-d', strtotime(Carbon::now()));
        $tagihan_kebelakang = Carbon::create($tanggal)->addMonth(-1)->toDateString();
        $user = (new GlobalController)->user_admin();
        $data['admin'] = $user['user_nama'];
        // dd($tagihan_kebelakang);
        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('reg_progres', '=', '5')
            ->where('reg_status', '!=', 'PAID')
            ->whereDate('reg_tgl_jatuh_tempo', '<', $tagihan_kebelakang)
            ->orderBy('reg_tgl_jatuh_tempo', 'ASC');
        $data['list_followup'] = $query->get();
        return view('Registrasi/list_followup', $data);
    }

    public function cek_perangkat(Request $request, $id)
    {
        if ($request->kelengkapan == 'Hilang') {
            $data_barang_keluar = Data_BarangKeluar::where('bk_idpel', $id)->where('bk_kategori', 'ONT')->orWhere('bk_kategori', 'ADAPTOR')->get();
            return response()->json($data_barang_keluar);
            if ($data_barang_keluar) {
                if ($data_barang_keluar->bk_idpel == $id) {
                    $dbk_adp = Data_BarangKeluar::where('bk_idpel', $id)->where('bk_kategori', 'ADAPTOR')->first();
                    $data['barang_id_adp'] = $dbk_adp->bk_id_barang;
                    $data['barang_id_ont'] = $data_barang_keluar->bk_id_barang;
                    $data['barang_sn'] = $cek_mac->barang_sn;
                    return response()->json($data);
                } else {
                    return response()->json('0');
                }
            } else {
                return response()->json('1');
            }
        } else {
            $cek_mac = Data_Barang::where('barang_mac', $request->mac)->where('barang_kategori', 'ONT')->first();
            if ($cek_mac) {
                $data_barang_keluar = Data_BarangKeluar::where('bk_id_barang', $cek_mac->barang_id)->first();
                if ($data_barang_keluar) {
                    if ($data_barang_keluar->bk_idpel == $id) {
                        $dbk_adp = Data_BarangKeluar::where('bk_idpel', $id)->where('bk_kategori', 'ADAPTOR')->first();
                        $data['barang_id_adp'] = $dbk_adp->bk_id_barang;
                        $data['barang_id_ont'] = $data_barang_keluar->bk_id_barang;
                        $data['barang_sn'] = $cek_mac->barang_sn;
                        return response()->json($data);
                    } else {
                        return response()->json('0');
                    }
                } else {
                    return response()->json('1');
                }
            } else {
                return response()->json('2');
            }
        }
    }
    public function cek_perangkat_hilang($id)
    {
        // $cek_mac = Data_Barang::where('barang_mac',$request->mac)->where('barang_kategori','ONT')->first();
        // if($cek_mac){
        $data_barang_keluar = Data_BarangKeluar::Join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->where('bk_idpel', $id)->where('bk_kategori', 'ONT')->first();
        // return response()->json($data_barang_keluar);
        if ($data_barang_keluar) {
            $dbk_adp = Data_BarangKeluar::where('bk_idpel', $id)->where('bk_kategori', 'ADAPTOR')->first();
            $data['barang_id_adp'] = $dbk_adp->bk_id_barang;
            $data['barang_id_ont'] = $data_barang_keluar->barang_id;
            $data['barang_sn'] = $data_barang_keluar->barang_sn;
            $data['barang_mac'] = $data_barang_keluar->barang_mac;
            return response()->json($data);
        } else {
            return response()->json('1');
        }
        // } else {
        //     return response()->json('2');
        // }
    }
}
