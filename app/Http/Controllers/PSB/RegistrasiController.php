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

class RegistrasiController extends Controller
{
    public function index()
    {
        $data['input_data'] = InputData::where('input_status', 'INPUT DATA')->get();
        $data['data_router'] = Router::all();
        $data['data_site'] = Data_Site::all();
        $data['data_pop'] = Data_pop::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();
        return view('Registrasi/registrasi', $data);
    }

    public function store(Request $request)
    {
        $sbiaya = SettingBiaya::first();
        // $sbiaya->biaya_psb

        // $admin = Auth::user()->name;
        $user = (new GlobalController)->user_admin();
        $admin = $user['user_nama'];
        $cek_sales = (new GlobalController)->role($request->reg_sales);

        $no_sk = (new GlobalController)->no_surat_keterang();


        // $site = Router::whereId($request->reg_router)->first();


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
        Session::flash('reg_stt_perangkat', $request->reg_stt_perangkat); #
        Session::flash('reg_nama_barang', $request->reg_nama_barang); #
        Session::flash('reg_mrek', $request->reg_mrek); #
        Session::flash('reg_mac', $request->reg_mac); #
        Session::flash('reg_sn', $request->reg_sn); #
        Session::flash('kode_pactcore', $request->kode_pactcore);
        Session::flash('kode_adaptor', $request->kode_adaptor);
        Session::flash('kode_ont', $request->kode_ont);
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
        if ($request->reg_profile) {
            $paket_nama = Paket::where('paket_id', $request->reg_profile)->first();
            Session::flash('paket_nama', $paket_nama->paket_nama);
        }

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
            'reg_stt_perangkat' => 'required',
            'reg_nama_barang' => 'required',
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
            'reg_inv_control' => 'required',
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
            'reg_stt_perangkat.required' => 'Status Perangkat tidak boleh kosong',
            'reg_nama_barang.required' => 'Nama Perangkat tidak boleh kosong',
            'reg_mrek.required' => 'Merek Perangkat tidak boleh kosong',
            'reg_mac.required' => 'Mac Address tidak boleh kosong',
            'reg_sn.required' => 'Serial Number Perangkat tidak boleh kosong',
            'kode_pactcore.required' => 'Kode Pactcore tidak boleh kosong',
            'kode_adaptor.required' => 'Kode Adaptor tidak boleh kosong',
            'kode_ont.required' => 'Kode ONT tidak boleh kosong',
            'reg_jenis_tagihan.required' => 'Jenis Tagihan tidak boleh kosong',
            'reg_harga.required' => 'Harga tidak boleh kosong',
            'input_subseles.required' => 'Sub Sales tidak boleh kosong',
            'reg_pop.required' => 'POP tidak boleh kosong',
            'reg_profile.required' => 'Paket tidak boleh kosong',
            'reg_inv_control.required' => 'Invoice Control tidak boleh kosong',
        ]);


        $dates = Carbon::now()->toDateTimeString();
        $tanggal = Carbon::now()->toDateString();
        // $tgl_aktif = date('d/m/Y', strtotime($dates));
        $tgl_pasang = Carbon::create($tanggal)->addDay(1)->toDateString();

        if ($cek_sales->role_id == 10 && $cek_sales->role_id == 13) {
            $data['reg_fee'] = $sbiaya->biaya_sales_continue;
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
        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
        $data['reg_nama_barang'] = $request->reg_nama_barang;
        $data['reg_site'] = $request->reg_site;
        $data['reg_pop'] = $request->reg_pop;
        $data['reg_mrek'] = $request->reg_mrek;
        $data['reg_mac'] = $request->reg_mac;
        $data['reg_sn'] = $request->reg_sn;
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
        $data['reg_inv_control'] = $request->reg_inv_control;
        $data['reg_status'] = '0';
        $data['reg_progres'] = '0';
        $update['input_maps'] =  $request->reg_maps;
        $update['input_status'] =  'REGIST';
        // dd($data);


        $router = Router::whereId($request->reg_router)->first();

        // dd($router);
        $profile = Paket::where('paket_id', $request->reg_profile)->first();

        $status = (new GlobalController)->whatsapp_status();

        if ($status->wa_status == 'Enable') {
            $pesan_pelanggan['status'] = '0';
            $pesan_group['status'] = '0';
        } else {
            $pesan_pelanggan['status'] = '10';
            $pesan_group['status'] = '10';
        }


        $pesan_pelanggan['ket'] = 'registrasi';
        $pesan_pelanggan['target'] = $request->reg_hp;
        $pesan_pelanggan['nama'] = $request->reg_nama;
        $pesan_pelanggan['pesan'] = 'Pelanggan Yth, 
Registrasi layanan internet berhasil, berikut data yang sudah terdaftar di sistem kami :

No.Layanan : *' . $request->reg_nolayanan . '*
Nama : *' . $request->reg_nama . '*
Alamat pasang : ' . $request->reg_alamat_pasang . '
Paket : *' . $profile->paket_nama . '*
Jenis tagihan : ' . $request->reg_jenis_tagihan . '
Biaya tagihan : ' . $request->reg_harga + $request->reg_ppn + $request->reg_dana_kerjasama + $request->reg_kode_unik + $request->reg_dana_kas . '
Tanggal Pasang : ' . date('d-m-Y', strtotime($tgl_pasang)) . '

Untuk melihat detail layanan dan pembayaran tagihan bisa melalui client area *https://ovallapp.com*

--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*
';

        Pesan::create($pesan_pelanggan);


        $pesan_group['ket'] = 'registrasi';
        $pesan_group['target'] = '120363262623415382@g.us';
        $pesan_group['nama'] = $request->reg_nama;
        $pesan_group['pesan'] = '               -- LIST PEMASANGAN --

Antrian pemasangan tanggal ' . date('d-m-Y', strtotime($tgl_pasang)) . ' 

No.Layanan : *' . $request->reg_nolayanan . '*
Nama : ' . $request->reg_nama . '
Alamat : ' . $request->reg_alamat_pasang .
            '
Paket : *' . $profile->paket_nama . '*
Jenis tagihan : ' . $request->reg_jenis_tagihan . '
Biaya tagihan : ' . $request->reg_harga + $request->reg_ppn + $request->reg_dana_kerjasama + $request->reg_kode_unik + $request->reg_dana_kas . '
Tanggal Pasang : ' . date('d-m-Y', strtotime($tgl_pasang)) . ' 

Diregistrasi Oleh : *' . $admin . '*
';


        Pesan::create($pesan_group);

        $update_barang['barang_status'] =  '1';
        $update_barang['barang_digunakan'] =  '1';
        $update_barang['barang_nama_pengguna'] = $request->reg_nama;

        $update_pactcore['barang_status'] =  '1';
        $update_pactcore['barang_digunakan'] =  '1';
        $update_pactcore['barang_nama_pengguna'] = $request->reg_nama;

        $update_adaptor['barang_status'] =  '1';
        $update_adaptor['barang_digunakan'] =  '1';
        $update_adaptor['barang_nama_pengguna'] = $request->reg_nama;


        $y = date('y');
        $m = date('m');

        $data_barang = Data_Barang::whereIn('barang_id', [$request->kode_ont, $request->kode_adaptor, $request->kode_pactcore])->get();




        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($request->reg_layanan == 'PPP') {

            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ip/pool/add', [
                    'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                    'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
                ]);
                $API->comm('/ppp/profile/add', [
                    'name' =>  $profile->paket_nama == '' ? '' : $profile->paket_nama,
                    'rate-limit' => $profile->paket_nama == '' ? '' : $profile->paket_nama,
                    'local-address' => $profile->paket_lokal == '' ? '' : $profile->paket_lokal,
                    'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                    'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                    'queue-type' => 'default-small' == '' ? '' : 'default-small',
                    'dns-server' => $router->router_dns == '' ? '' : $router->router_dns,
                    'disabled' => 'yes',
                    'only-one' => 'yes',
                ]);

                $profile = $API->comm('/ppp/profile/print', [
                    '?name' => $profile->paket_nama,
                ]);
                if ($profile) {
                    $API->comm('/ppp/secret/add', [
                        'name' => $request->reg_username == '' ? '' : $request->reg_username,
                        'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                        'service' => 'pppoe',
                        'profile' => $paket_nama->paket_nama  == '' ? 'default' : $paket_nama->paket_nama,
                        'comment' => 'REGIST-' . $dates == '' ? '' : 'REGIST-' . $dates,
                        'disabled' => 'yes',
                    ]);

                    Registrasi::create($data);
                    // dd($data);
                    InputData::where('id', $request->reg_idpel)->update($update);
                    Data_Barang::where('barang_id', $request->kode_pactcore)->update($update_pactcore);
                    Data_Barang::where('barang_id', $request->kode_adaptor)->update($update_adaptor);
                    Data_Barang::where('barang_id', $request->kode_ont)->update($update_barang);
                    foreach ($data_barang as $db) {
                        Data_BarangKeluar::create([

                            'bk_id' => $no_sk,
                            'bk_jenis_laporan' => 'Instalasi',
                            'bk_id_barang' => $db->barang_id,
                            'bk_id_tiket' => '0',
                            'bk_kategori' => $db->barang_kategori,
                            'bk_satuan' => $db->barang_satuan,
                            'bk_nama_barang' => $db->barang_nama,
                            'bk_model' => $db->barang_merek,
                            'bk_mac' => $db->barang_mac,
                            'bk_sn' => $db->barang_sn,
                            'bk_jumlah' => 1,
                            'bk_keperluan' => 'Instalasi Pemasangan Baru',
                            'bk_foto_awal' => '-',
                            'bk_foto_akhir' => '-',
                            'bk_nama_penggunan' => $request->reg_nama,
                            'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                            'bk_admin_input' => $admin,
                            'bk_penerima' => 'Teknisi',
                            'bk_status' => 1,
                            'bk_keterangan' => $db->barang_ket,
                            'bk_harga' => $db->barang_harga,
                        ]);
                    }


                    $notifikasi = array(
                        'pesan' => 'Berhasil menambahkan pelanggan',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.index')->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Gagal menambah pelanggan..Paket Tidak tersedia pada router',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.psb.index')->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambahkan pelanggan. Router Dissconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.index')->with($notifikasi);
            }
        } elseif ($request->reg_layanan == 'HOTSPOT') {


            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ip/hotspot/user/add', [
                    'name' => $request->reg_username == '' ? '' : $request->reg_username,
                    'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                    'profile' => $paket_nama->paket_nama  == '' ? 'default' : $paket_nama->paket_nama,
                    'comment' => $request->reg_nama  == '' ? '' : $request->reg_nama,
                    'disabled' => 'yes',
                ]);
                // dd($request->reg_nama);
                Registrasi::create($data);
                Data_Barang::where('barang_id', $request->kode_pactcore)->update($update_pactcore);
                Data_Barang::where('barang_id', $request->kode_adaptor)->update($update_adaptor);
                Data_Barang::where('barang_id', $request->kode_ont)->update($update_barang);
                foreach ($data_barang as $db) {
                    Data_BarangKeluar::create([

                        'bk_id' => $no_sk,
                        'bk_jenis_laporan' => 'Instalasi',
                        'bk_id_barang' => $db->barang_id,
                        'bk_id_tiket' => '0',
                        'bk_kategori' => $db->barang_kategori,
                        'bk_satuan' => $db->barang_satuan,
                        'bk_nama_barang' => $db->barang_nama,
                        'bk_model' => $db->barang_merek,
                        'bk_mac' => $db->barang_mac,
                        'bk_sn' => $db->barang_sn,
                        'bk_jumlah' => 1,
                        'bk_keperluan' => 'Instalasi Pemasangan Baru',
                        'bk_foto_awal' => '-',
                        'bk_foto_akhir' => '-',
                        'bk_nama_penggunan' => $request->reg_nama,
                        'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                        'bk_admin_input' => $admin,
                        'bk_penerima' => 'Teknisi',
                        'bk_status' => 1,
                        'bk_keterangan' => $db->barang_ket,
                    ]);
                }

                $notifikasi = array(
                    'pesan' => 'Berhasil menambahkan pelanggan',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index')->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambahkan pelanggan. Router Dissconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.index')->with($notifikasi);
            }
        }



        // return redirect()->route('admin.reg.registrasi_api', ['id' => $data['reg_idpel']]);
    }

    public function delete_registrasi($id)
    {

        $admin = (new GlobalController)->user_admin()['user_id'];
        $data_pelanggan = Registrasi::join('routers', 'routers.id', '=', 'registrasis.reg_router')->where('reg_idpel', $id)->first();
        // $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
        $user = $data_pelanggan->router_username;
        $pass = $data_pelanggan->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        // dd($data_pelanggan->reg_username);

        if ($API->connect($ip, $user, $pass)) {
            $update['input_status'] =  'INPUT DATA';
            $update_barang['barang_status'] =  '0';
            $update_barang['barang_digunakan'] = '0';
            $update_barang['barang_penerima'] = $admin;
            Data_Barang::where('barang_id', $data_pelanggan->reg_kode_pactcore)->update($update_barang);
            Data_Barang::where('barang_id', $data_pelanggan->reg_kode_adaptor)->update($update_barang);
            Data_Barang::where('barang_id', $data_pelanggan->reg_kode_ont)->update($update_barang);
            $cek = Data_BarangKeluar::whereIn('bk_id_barang', [$data_pelanggan->reg_kode_pactcore, $data_pelanggan->reg_kode_adaptor, $data_pelanggan->reg_kode_ont])->delete();

            // echo $cek;

            InputData::where('id', $data_pelanggan->reg_idpel)->update($update);
            $data = Registrasi::where('reg_idpel', $id);
            if ($data) {
                $data->delete();
            }
            // dd('drlet');
            $cek_secret = $API->comm('/ppp/secret/print', [
                '?name' => $data_pelanggan->reg_username,
            ]);
            if ($cek_secret) {
                $API->comm('/ppp/secret/remove', [
                    '.id' => $cek_secret[0]['.id'],
                ]);

                $notifikasi = array(
                    'pesan' => 'Hapus Data Registrasi Berhasil berhasil',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index', ['id' => $id])->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Hapus data registrasi berhasil. Secret tidak ditemukan pada Router',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index', ['id' => $id])->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Maaf..!! Router Disconnected',
                'alert' => 'error',
            );
            return redirect()->route('admin.psb.form_data_pelanggan', ['id' => $id])->with($notifikasi);
        }
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
    public function validasi_pachcore(Request $request, $id)
    {
        $kode_pact = Data_Barang::where("barang_id", $id)->where("barang_status", '0')
            ->where("barang_kategori", 'PACTCORE')->first();

        return response()->json($kode_pact);
    }
    public function validasi_adaptor(Request $request, $id)
    {
        $kode_adp = Data_Barang::where("barang_id", $id)->where("barang_status", '0')
            ->where("barang_kategori", 'ADAPTOR')->first();

        return response()->json($kode_adp);
    }
    public function validasi_ont(Request $request, $id)
    {
        $kode_ont = Data_Barang::where("barang_id", $id)->where("barang_status", '0')
            ->where("barang_kategori", 'ONT')->first();

        return response()->json($kode_ont);
    }
    // public function validasi_ont(Request $request, $id)
    // {
    //     $kode_ont = SubBarang::where("id_subbarang", $id)->where("subbarang_status", '0')
    //         ->where("subbarang_ktg", 'ONT')->first();

    //     return response()->json($kode_ont);
    // }
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
        $pdf = App::make('dompdf.wrapper');
        $html = view('PSB/print_berita_acara', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download('Berita_Acara_' . $sales . '.pdf');
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
    public function operasional()
    {

        $data['data_bank'] = SettingAkun::where('id', '>', 1)->get();
        $data['data_user'] = User::where('id', '>', 10)->get();
        $data['data_biaya'] = SettingBiaya::first();
        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->orderBy('tgl', 'DESC');

        $data['data_registrasi'] = $query->get();

        return view('PSB/operasional', $data);
    }
    public function konfirm_pencairan(Request $request)
    {
        $admin = Auth::user()->id;
        $nama_admin = Auth::user()->name;
        $biaya = SettingBiaya::first();
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

        Teknisi::whereIn('teknisi_idpel', $request->idpel)->where('teknisi_status', '1')->where('teknisi_job', 'PSB')->update(
            [
                'teknisi_keuangan_userid' => $admin,
                'teknisi_status' => 2,
            ]
        );
        $count = count($request->idpel);
        $total = ($biaya->biaya_psb + $biaya->biaya_sales) * $count;
        $psb = $biaya->biaya_psb * $count;
        $marketing = $biaya->biaya_sales * $count;

        $cek_saldo = (new GlobalController)->mutasi_jurnal();

        if ($cek_saldo['saldo'] >= $total) {
            Jurnal::create([
                'jurnal_id' => time(),
                'jurnal_tgl' => $data['input_tgl'],
                'jurnal_uraian' => 'Pencairan PSB oleh ' . $nama_admin . ' Sebanyak ' . $count . ' Pelanggan',
                'jurnal_kategori' => 'PENGELUARAN',
                'jurnal_keterangan' => 'PSB',
                'jurnal_admin' => $admin,
                'jurnal_penerima' => $request->penerima,
                'jurnal_metode_bayar' => $request->akun,
                'jurnal_debet' => $psb,
                'jurnal_status' => 1,
            ]);
            Jurnal::create([
                'jurnal_id' => time(),
                'jurnal_tgl' => $data['input_tgl'],
                'jurnal_uraian' => 'Pencairan MARKETING oleh ' . $nama_admin . ' Sebanyak ' . $count . ' Pelanggan',
                'jurnal_kategori' => 'PENGELUARAN',
                'jurnal_keterangan' => 'MARKETING',
                'jurnal_admin' => $admin,
                'jurnal_penerima' => $request->penerima,
                'jurnal_metode_bayar' => $request->akun,
                'jurnal_debet' => $marketing,
                'jurnal_status' => 1,
            ]);

            Registrasi::where('reg_progres', '4')->whereIn('reg_idpel', $request->idpel)->update(['reg_progres' => '5']);

            $notifikasi = 'berhasil';
            return response()->json($notifikasi);
        } else {
            $notifikasi = 'saldo_tidak_cukup';
            return response()->json($notifikasi);
        }
    }

    public function bukti_kas_keluar($id)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['biaya_sales'] = SettingBiaya::first();
        // dd($data['biaya_sales']['biaya_sales_continue']);
        $user = (new GlobalController)->user_admin();
        $data['nama_admin'] = $user['user_nama'];
        $data['id_admin'] = $user['user_id'];
        $teknisi = Teknisi::where('teknisi_idpel', $id)->where('teknisis.teknisi_job', 'PSB')->where('teknisis.teknisi_psb', '>', '0')->first();
        if ($teknisi) {
            $data['kas'] =  Registrasi::select('registrasis.*', 'input_data.*', 'pakets.*', 'teknisis.*', 'teknisis.created_at as mulai',)
                ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
                ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
                ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'registrasis.reg_idpel')
                ->where('registrasis.reg_idpel', $id)
                ->where('teknisis.teknisi_job', 'PSB')
                ->where('teknisis.teknisi_psb', '>', '0')
                ->first();

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
            return view('PSB/bukti_kas_keluar', $data);
        } else {
            $notifikasi = [
                'pesan' => 'Teknisi tidak ditemukan',
                'alert' => 'error',
            ];
            return redirect()->route('admin.psb.index')->with($notifikasi);
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

    public function sambung_kembali(Request $request, $idpel)
    {

        $swaktu = SettingWaktuTagihan::first();
        $admin = Auth::user()->name;
        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('reg_idpel', $idpel)->first();


        $dates = Carbon::now()->toDateTimeString();
        $tanggal = Carbon::now()->toDateString();
        // $tgl_aktif = date('d/m/Y', strtotime($dates));
        $tgl_pasang = Carbon::create($tanggal)->addDay(1)->toDateString();
        $inv_tgl_isolir1blan = Carbon::create($tanggal)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
        $tagihan_tanpa_ppn = $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik;
        $periode1blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(1)->toDateString();


        $update_barang['subbarang_status'] =  '1';
        $update_barang['subbarang_keluar'] = '1';
        $update_barang['subbarang_stok'] = '0';
        $update_barang['subbarang_keterangan'] = 'SAMBUNG KEMBLI ' . $query->input_nama;
        $update_barang['subbarang_admin'] = $admin;
        $update_barang['subbarang_tgl_keluar'] = date('Y-m-d H:m:s', strtotime(Carbon::now()));
        $update_adaptor['subbarang_status'] =  '1';
        $update_adaptor['subbarang_keluar'] = '1';
        $update_adaptor['subbarang_stok'] = '0';
        $update_adaptor['subbarang_keterangan'] = 'SAMBUNG KEMBLI ' . $query->input_nama;
        $update_adaptor['subbarang_admin'] = $admin;
        $update_adaptor['subbarang_tgl_keluar'] = date('Y-m-d H:m:s', strtotime(Carbon::now()));





        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($query->reg_layanan == 'PPP') {

            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ip/pool/add', [
                    'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                    'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
                ]);
                $API->comm('/ppp/profile/add', [
                    'name' =>  $query->paket_nama == '' ? '' : $query->paket_nama,
                    'rate-limit' => $query->paket_nama == '' ? '' : $query->paket_nama,
                    'local-address' => $query->paket_lokal == '' ? '' : $query->paket_lokal,
                    'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                    'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                    'queue-type' => 'default-small' == '' ? '' : 'default-small',
                    'dns-server' => $query->router_dns == '' ? '' : $query->router_dns,
                    'disabled' => 'no',
                    'only-one' => 'yes',
                ]);

                $profile = $API->comm('/ppp/profile/print', [
                    '?name' => $query->paket_nama,
                ]);
                if ($profile) {
                    $API->comm('/ppp/secret/add', [
                        'name' => $query->reg_username == '' ? '' : $query->reg_username,
                        'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                        'service' => 'pppoe',
                        'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                        'comment' => 'SK-' . $dates == '' ? '' : 'SK-' . $dates,
                        'disabled' => 'no',
                    ]);




                    $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                    $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                    $inv['inv_tgl_tagih'] = $tanggal;
                    $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                    $inv['inv_periode'] = $periode1blan;
                    $inv['inv_tgl_pasang'] = $tanggal;
                    $inv['inv_status'] = 'UNPAID';
                    $inv['inv_idpel'] = $query->reg_idpel;
                    $inv['inv_nolayanan'] = $query->reg_nolayanan;
                    $inv['inv_nama'] = $query->input_nama;
                    $inv['inv_jenis_tagihan'] = $query->reg_jenis_tagihan;
                    $inv['inv_profile'] = $query->paket_nama;
                    $inv['inv_mitra'] = 'SYSTEM';
                    $inv['inv_kategori'] = 'OTOMATIS';
                    $inv['inv_diskon'] = '0';
                    $inv['inv_note'] = $query->input_nama;



                    $sub_inv['subinvoice_deskripsi'] = $query->paket_nama . ' ( ' . $inv['inv_periode'] . ' )';
                    $sub_inv['subinvoice_status'] = '0';
                    $sub_inv['subinvoice_harga'] = $query->reg_harga;
                    $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                    $sub_inv['subinvoice_total'] = $inv['inv_total'];
                    $sub_inv['subinvoice_qty'] = '1';

                    $cek_inv = Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->first();
                    if ($cek_inv) {
                        $inv['inv_id'] = $cek_inv->inv_id;
                        $sub_inv['subinvoice_id'] = $inv['inv_id'];
                        Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->update($inv);
                        SubInvoice::where('subinvoice_id', $sub_inv['subinvoice_id'])->update($sub_inv);
                    } else {
                        $inv['inv_id'] = (new GlobalController)->no_inv();
                        $sub_inv['subinvoice_id'] = $inv['inv_id'];
                        Invoice::create($inv);
                        SubInvoice::create($sub_inv);
                    }


                    $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                    $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                    $pelanggan['reg_status'] = 'UNPAID';
                    $pelanggan['reg_progres'] = '5';

                    $pelanggan['reg_kode_adaptor'] = $request->kode_adaptor;
                    $pelanggan['reg_kode_ont'] = $request->kode_ont;

                    $pelanggan['reg_mrek'] = $request->sam_mrek;
                    $pelanggan['reg_stt_perangkat'] = $request->sam_stt_perangkat;
                    $pelanggan['reg_mac'] = $request->sam_mac;
                    $pelanggan['reg_sn'] = $request->sam_sn;
                    Registrasi::where('reg_idpel', $idpel)->update($pelanggan);


                    SubBarang::where('id_subbarang', $request->kode_adaptor)->update($update_adaptor);
                    SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);


                    $notifikasi = array(
                        'pesan' => 'Berhasil menambahkan pelanggan',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.index')->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Gagal menambah pelanggan..Paket Tidak tersedia pada router',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.psb.index')->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambahkan pelanggan. Router Dissconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.index')->with($notifikasi);
            }
        } elseif ($request->reg_layanan == 'HOTSPOT') {


            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ip/hotspot/user/add', [
                    'name' => $query->reg_username == '' ? '' : $query->reg_username,
                    'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                    'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                    'comment' => $query->reg_nama  == '' ? '' : $query->reg_nama,
                    'disabled' => 'no',
                ]);
                $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                $inv['inv_tgl_tagih'] = $tanggal;
                $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                $inv['inv_periode'] = $periode1blan;
                $inv['inv_tgl_pasang'] = $tanggal;
                $inv['inv_status'] = 'UNPAID';
                $inv['inv_idpel'] = $query->reg_idpel;
                $inv['inv_nolayanan'] = $query->reg_nolayanan;
                $inv['inv_nama'] = $query->input_nama;
                $inv['inv_jenis_tagihan'] = $query->reg_jenis_tagihan;
                $inv['inv_profile'] = $query->paket_nama;
                $inv['inv_mitra'] = 'SYSTEM';
                $inv['inv_kategori'] = 'OTOMATIS';
                $inv['inv_diskon'] = '0';
                $inv['inv_note'] = $query->input_nama;



                $sub_inv['subinvoice_deskripsi'] = $query->paket_nama . ' ( ' . $inv['inv_periode'] . ' )';
                $sub_inv['subinvoice_status'] = '0';
                $sub_inv['subinvoice_harga'] = $query->reg_harga;
                $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                $sub_inv['subinvoice_total'] = $inv['inv_total'];
                $sub_inv['subinvoice_qty'] = '1';

                $cek_inv = Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->first();
                if ($cek_inv) {
                    $inv['inv_id'] = $cek_inv->inv_id;
                    $sub_inv['subinvoice_id'] = $inv['inv_id'];
                    Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->update($inv);
                    SubInvoice::where('subinvoice_id', $sub_inv['subinvoice_id'])->update($sub_inv);
                } else {
                    $inv['inv_id'] = rand(10000, 19999);
                    $sub_inv['subinvoice_id'] = $inv['inv_id'];
                    Invoice::create($inv);
                    SubInvoice::create($sub_inv);
                }


                $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                $pelanggan['reg_status'] = 'UNPAID';
                $pelanggan['reg_progres'] = '5';

                $pelanggan['reg_kode_adaptor'] = $request->kode_adaptor;
                $pelanggan['reg_kode_ont'] = $request->kode_ont;

                $pelanggan['reg_mrek'] = $request->sam_mrek;
                $pelanggan['reg_stt_perangkat'] = $request->sam_stt_perangkat;
                $pelanggan['reg_mac'] = $request->sam_mac;
                $pelanggan['reg_sn'] = $request->sam_sn;
                Registrasi::where('reg_idpel', $idpel)->update($pelanggan);


                SubBarang::where('id_subbarang', $request->kode_adaptor)->update($update_adaptor);
                SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);

                $notifikasi = array(
                    'pesan' => 'Berhasil menambahkan pelanggan',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index')->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambahkan pelanggan. Router Dissconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.index')->with($notifikasi);
            }
        }
    }



    public function form_data_pelanggan($id)
    {

        // $data_barang = Registrasi::where('reg_router','17')->get();
        // foreach ($data_barang as $key) {
        //     echo $key->reg_site.'<br>';
        //     Registrasi::where('reg_router','17')->update([
        //         'reg_site'=> '1',
        //         'reg_pop'=> '1',
        //         // 'reg_olt'=> '1.4.1',
        //     ]);

        // }

        // dd('test');
        $user = (new globalController)->user_admin();
        $data['user_nama'] = $user['user_nama'];
        $data['user_id'] = $user['user_id'];
        $data['user'] = User::get();

        $data['tgl_akhir'] = date('t', strtotime(Carbon::now()));
        // dd($data['tgl_akhir']);
        $status_inet = (new NocController)->status_inet($id);
        // dd($status_inet['status']);
        $data['input_data'] = InputData::all();
        $data['data_router'] = Router::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();
        $data['data_teknisi'] = (new GlobalController)->getTeknisi();
        // dd($data['data_teknisi']);

        $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('data__sites', 'data__sites.site_id', '=', 'registrasis.reg_site')
            ->join('data_pops', 'data_pops.pop_id', '=', 'registrasis.reg_pop')
            // ->join('data__olts', 'data__olts.olt_id_pop', '=', 'registrasis.reg_olt')
            // ->join('data__odcs', 'data__odcs.odc_id', '=', 'registrasis.reg_odc')
            // ->join('data__odps', 'data__odps.odp_id', '=', 'registrasis.reg_odp')
            ->where('input_data.id', $id)
            ->first();
        // dd($data['data']); 
        // dd($data['site'])->pop_nama;
        // $data['data_site'] = Data_Site::get();
        $data['router'] = Router::join('data_pops', 'data_pops.pop_id', '=', 'routers.router_id_pop')
            // ->join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
            ->get();
        // ->where('pop_id', $data['data']->pop_id)->get();
        // dd($data['data']->reg_pop);
        $data['data_olt'] = Data_pop::join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
            ->where('pop_id', $data['data']->reg_pop)->get();
        $data['status'] = $status_inet['status'];
        $data['uptime'] = $status_inet['uptime'];
        $data['address'] = $status_inet['address'];
        $data['status_secret'] = $status_inet['status_secret'];
        return view('Registrasi/form_data_pelanggan', $data);
    }

    public function proses_edit_pelanggan(Request $request, $id)
    {
        Session::flash('reg_site', $request->reg_site);
        Session::flash('reg_pop', $request->reg_pop);
        Session::flash('reg_router', $request->reg_router);
        Session::flash('reg_olt', $request->reg_olt);
        Session::flash('reg_odc', $request->reg_odc);
        Session::flash('reg_odp', $request->reg_odp);
        Session::flash('reg_mac_olt', $request->reg_mac_olt);
        Session::flash('reg_onuid', $request->reg_onuid);
        Session::flash('reg_slot_odp', $request->reg_slot_odp);
        Session::flash('reg_kode_dropcore', $request->reg_kode_dropcore);
        Session::flash('reg_before', $request->reg_before);
        Session::flash('reg_after', $request->reg_after);
        Session::flash('reg_penggunaan_dropcore', $request->reg_penggunaan_dropcore);
        Session::flash('reg_koodinat_odp', $request->reg_koodinat_odp);
        Session::flash('teknisi1', $request->teknisi1);
        Session::flash('teknisi2', $request->teknisi2);
        Session::flash('reg_in_ont', $request->reg_in_ont);
        Session::flash('input_koordinat', $request->input_koordinat);

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
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 1) {
                $notifikasi = array(
                    'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 2) {
                $notifikasi = array(
                    'pesan' => 'Router Discconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
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
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 1) {
                $notifikasi = array(
                    'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
            } elseif ($API == 2) {
                $notifikasi = array(
                    'pesan' => 'Router Discconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_data_pelanggan', ['id' => $id])->with($notifikasi);
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
        $data['tgl_akhir'] = date('t', strtotime(Carbon::now()));
        // dd($data['tgl_akhir']);
        $status_inet = (new NocController)->status_inet($id);
        // dd($status_inet['status']);
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
                'reg_kode_ont' => 0,
                'reg_kode_adaptor' => 0,
                'reg_mac' => '',
                'reg_sn' => '',
                'reg_mrek' => '',
            ]);
            Data_Deaktivasi::create([
                'deaktivasi_idpel' => $id,
                'deaktivasi_mac' => $request->deaktivasi_mac,
                'deaktivasi_sn' => $request->deaktivasi_sn,
                'deaktivasi_kelengkapan_perangkat' => $request->deaktivasi_kelengkapan_perangkat,
                'deaktivasi_tanggal_pengambilan' => $tgl_ambil_perangkat,
                'deaktivasi_pengambil_perangkat' => $request->deaktivasi_pengambil_perangkat,
                'deaktivasi_admin' => $request->deaktivasi_admin,
                'deaktivasi_alasan_deaktivasi' => $request->deaktivasi_alasan_deaktivasi,
                'deaktivasi_pernyataan' => $request->deaktivasi_pernyataan,
                'deaktivasi_admin' =>  $nama_admin,
            ]);
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
            return redirect()->route('admin.psb.index')->with($notifikasi);
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



    // public function berita_acara_deaktivasi($id)
    // {

    //     $data['profile_perusahaan'] = SettingAplikasi::first();
    //     $data['nama_admin'] = Auth::user()->name;
    //     $data['berita_acara'] =  Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
    //         ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
    //         ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
    //         // ->join('users', 'users.id', '=', 'input_data.input_sales')
    //         ->where('registrasis.reg_idpel', $id)
    //         ->first();
    //     $seles = User::whereId($data['berita_acara']->input_sales)->first();
    //     if ($seles) {
    //         $data['seles'] = $seles->name;
    //     } else {
    //         $data['seles'] = '-';
    //     }

    //     // dd($data);
    //     $nama = InputData::where('id', $id)->first();
    //     if ($nama) {
    //         $sales = $nama->input_nama;
    //     } else {
    //         $sales = '-';
    //     }
    //     // $pdf = App::make('dompdf.wrapper');
    //     // $html = view('PSB/print_berita_acara', $data)->render();
    //     // $pdf->loadHTML($html);
    //     // $pdf->setPaper('A4', 'potraid');
    //     // return $pdf->download('Berita_Acara_' . $sales . '.pdf');

    //     return view('Registrasi/berita_acara_deaktivasi', $data);
    // }
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
}
