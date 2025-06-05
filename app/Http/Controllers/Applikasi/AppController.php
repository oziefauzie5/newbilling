<?php

namespace App\Http\Controllers\Applikasi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Kelurahan;
use App\Models\Aplikasi\Data_RT;
use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingTripay;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Permission;
use App\Models\Transaksi\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    public function index()
    {
        $SettingTripay = SettingTripay::where('corporate_id',Session::get('corp_id'))->first();
        if (isset($SettingTripay) == NULL) {
            $data['tripay_merchant'] = " ";
            $data['tripay_kode_merchant'] = " ";
            $data['tripay_url_callback'] = " ";
            $data['tripay_apikey'] = "";
            $data['tripay_privatekey'] = '';
            $data['tripay_admin_topup'] = '';
        } else {
            $data['tripay_merchant'] = 'Tripay';
            $data['tripay_kode_merchant'] = $SettingTripay->tripay_kode_merchant;
            $data['tripay_url_callback'] = $SettingTripay->tripay_url_callback;
            $data['tripay_apikey'] = $SettingTripay->tripay_apikey;
            $data['tripay_privatekey'] = $SettingTripay->tripay_privatekey;
            $data['tripay_admin_topup'] = $SettingTripay->tripay_admin_topup;
        }
        $SettingAplikasi = SettingAplikasi::where('corporate_id',Session::get('corp_id'))->first();

        if (isset($SettingAplikasi) == NULL) {
            $data['app_nama'] = " ";
            $data['app_brand'] = " ";
            $data['app_alamat'] = " ";
            $data['app_npwp'] = "";
            $data['app_logo'] = "";
            $data['app_favicon'] = "";
            $data['app_link_admin'] = "";
            $data['app_link_pelanggan'] = "";
        } else {
            $data['app_nama'] = $SettingAplikasi->app_nama;
            $data['app_brand'] = $SettingAplikasi->app_brand;
            $data['app_alamat'] = $SettingAplikasi->app_alamat;
            $data['app_npwp'] = $SettingAplikasi->app_npwp;
            $data['app_logo'] = $SettingAplikasi->app_logo;
            $data['app_favicon'] = $SettingAplikasi->app_favicon;
            $data['app_link_admin'] = $SettingAplikasi->app_link_admin;
            $data['app_link_pelanggan'] = $SettingAplikasi->app_link_pelanggan;
        }
        $SettingBiaya = SettingBiaya::where('corporate_id',Session::get('corp_id'))->first();

        if (isset($SettingBiaya) == NULL) {
            $data['biaya_ppn'] = "0";
            $data['biaya_bph_uso'] = "0";
            $data['biaya_sales'] = "0";
            $data['biaya_psb'] = "0";
            $data['biaya_pasang'] = "0";
            $data['biaya_kas'] = "0";
        } else {
            $data['biaya_ppn'] = $SettingBiaya->biaya_ppn;
            $data['biaya_bph_uso'] = $SettingBiaya->biaya_bph_uso;
            $data['biaya_sales'] = $SettingBiaya->biaya_sales;
            $data['biaya_psb'] = $SettingBiaya->biaya_psb;
            $data['biaya_pasang'] = $SettingBiaya->biaya_pasang;
        }
        $SettingBiaya = SettingWaktuTagihan::where('corporate_id',Session::get('corp_id'))->first();

        if (isset($SettingBiaya) == NULL) {
            $data['wt_jeda_isolir_hari'] = "0";
            $data['wt_jeda_tagihan_pertama'] = "0";
            $data['wt_tgl_isolir'] = "10";
        } else {
            $data['wt_jeda_isolir_hari'] = $SettingBiaya->wt_jeda_isolir_hari;
            $data['wt_jeda_tagihan_pertama'] = $SettingBiaya->wt_jeda_tagihan_pertama;
            $data['wt_tgl_isolir'] = $SettingBiaya->wt_tgl_isolir;
        }


        $data['SettingAkun'] =  (new SettingAkun())->SettingAkun()->get();
        // // dd($data['SettingAkun']);
        // $count = SettingAkun::where('corporate_id',Session::get('corp_id'))->count();
        // if ($count == 0) {
        //     $data['akun_id'] = $Settingakun_id = 1;
        // } else {
        //     $data['akun_id'] = $Settingakun_id = $count + 1;
        // }
        return view('Applikasi/index', $data);
    }
    public function tripay_store(Request $request)
    {
        SettingTripay::updateOrCreate(
            [
                'corporate_id' => Session::get('corp_id'),
            ],
            [
                'corporate_id' => Session::get('corp_id'),
                'tripay_merchant' => 'Tripay',
                'tripay_kode_merchant' => $request->tripay_kode_merchant,
                'tripay_url_callback' => $request->tripay_url_callback,
                'tripay_apikey' => $request->tripay_apikey,
                'tripay_privatekey' => $request->tripay_privatekey,
                'tripay_admin_topup' => $request->tripay_admin_topup,
            ]
        );

        SettingAkun::updateOrCreate(
            [
                'corporate_id' => Session::get('corp_id'),
            ],
            [
                'corporate_id' => Session::get('corp_id'),
                'akun_pemilik' => 'SYSTEM',
                'akun_rekening' => '0',
                'akun_nama' => 'TRIPAY',
                'akun_status' => 'Enable',
                'akun_kategori' => 'PEMBAYARAN',
           ]
    );

        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Tripay',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }

    // ===============================================END TRIPAY=================================================
    // =================================================akun=================================================
    public function akun_store(Request $request)
    {
        Session::flash('akun_rekening');
        $request->validate([
            'akun_id' => 'unique:setting_akuns',
            'akun_rekening' => 'unique:setting_akuns',
        ], [
            'akun_id.unique' => 'Id Akun sudah terdaftar',
            'akun_rekening.unique' => 'Nomor Rekening sudah terdaftar',
        ]);
        $cek_akun = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_nama','TUNAI')->count();
        if ($cek_akun == 0) {
            $tunai['corporate_id'] = Session::get('corp_id');
            $tunai['akun_nama'] = 'TUNAI';
            $tunai['akun_type'] = 'TUNAI';
            $tunai['akun_pemilik'] = 'SYSTEM';
            $tunai['akun_rekening'] = '0';
            $tunai['akun_status'] = 'Enable';
            $tunai['akun_kategori'] = 'PEMBAYARAN';
            
            $akun['corporate_id'] = Session::get('corp_id');
            $akun['akun_nama'] = $request->nama_akun;
            $akun['akun_type'] = $request->akun_type;
            $akun['akun_rekening'] = $request->akun_rekening;
            $akun['akun_pemilik'] = $request->nama_pemilik;
            $akun['akun_status'] = 'Enable';
            $akun['akun_kategori'] = $request->akun_kategori;
            
            SettingAkun::create($tunai);
            SettingAkun::create($akun);
        } else {
            SettingAkun::create(
                [
                    // 'id' => $request->akun_id,
                    'corporate_id' => Session::get('corp_id'),
                    'akun_nama' => $request->nama_akun,
                    'akun_type' => $request->akun_type,
                    'akun_rekening' => $request->akun_rekening,
                    'akun_pemilik' => $request->nama_pemilik,
                    'akun_status' => 'Enable',
                    'akun_kategori' => $request->akun_kategori,
                ]
            );
        }

        $notifikasi = array(
            'pesan' => 'Menambah Akun Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }
    public function akun_edit(Request $request, $id)
    {
        SettingAkun::where('corporate_id',Session::get('corp_id'))->whereId($id)->update(
            [
                'akun_id' => $request->akun_id,
                'akun_type' => $request->akun_type,
                'akun_nama' => $request->nama_akun,
                'akun_rekening' => $request->akun_rekening,
                'akun_pemilik' => $request->akun_pemilik,
                'akun_status' => 'Enable',
                'akun_kategori' => $request->akun_kategori,
            ]
        );
        $notifikasi = array(
            'pesan' => 'Edit Akun Berhasil ',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }
    public function akun_delete($id)
    {
        $data = SettingAkun::where('corporate_id',Session::get('corp_id'))->find($id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = array(
            'pesan' => 'Hapus Akun Berhasil ',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }
    // ================================================END akun================================================

    public function aplikasi_store(Request $request)
    {
        // Session::flash('app_nama', $request->app_nama);
        // Session::flash('app_brand', $request->app_brand);
        // Session::flash('app_alamat', $request->app_alamat);
        // Session::flash('app_npwp', $request->app_npwp);

        $request->validate([
            'app_logo' => 'mimes:png|max:1028',
            'app_favicon' => 'mimes:png|max:1028',
        ], [
            'app_logo.mimes' => 'Upload dengan format png',
            'app_logo.max' => 'File terlalu besar. Max upload 1Mb',
            'app_favicon.mimes' => 'Upload dengan format png',
            'app_favicon.max' => 'File terlalu besar. Max upload 1Mb',

        ]);

        if ($request->file('app_logo')) {
            $photo = $request->file('app_logo');
            $filename1 = $photo->getClientOriginalName();
            $path = 'profile_perusahaan/' . $filename1;
            Storage::disk('public')->put($path, file_get_contents($photo));
        } else {
            $filename1 = '';
        }
        if ($request->file('app_favicon')) {
            $app_favicon = $request->file('app_favicon');
            $filename2 = $app_favicon->getClientOriginalName();
            $path = 'profile_perusahaan/' . $filename2;
            Storage::disk('public')->put($path, file_get_contents($app_favicon));
        } else {
            $filename2 = '';
        }

        // dd($filename2);.
        $cek = SettingAplikasi::where('corporate_id', Session::get('corp_id'))->count();
        if ($cek == 0) {

            SettingAplikasi::create(
                [
                    'id' => '1',
                    'app_nama' => $request->app_nama,
                    'app_brand' => $request->app_brand,
                    'app_alamat' => $request->app_alamat,
                    'app_npwp' => $request->app_npwp,
                    'app_logo' => $filename1,
                    'app_favicon' => $filename2,
                    'app_link_admin' => $request->app_link_admin,
                    'app_link_pelanggan' => $request->app_link_pelanggan,
                ]
            );
        } else {
            SettingAplikasi::where('corporate_id', Session::get('corp_id'))->update(
                [
                    'app_nama' => $request->app_nama,
                    'app_brand' => $request->app_brand,
                    'app_alamat' => $request->app_alamat,
                    'app_npwp' => $request->app_npwp,
                    'app_logo' => $filename1,
                    'app_favicon' => $filename2,
                    'app_link_admin' => $request->app_link_admin,
                    'app_link_pelanggan' => $request->app_link_pelanggan,
                ]
            );
        }
        $notifikasi = array(
            'pesan' => 'Menambah pengaturan Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }


    // ==============================================END APLIKASI==============================================
    // ==============================================START BIAYA==============================================
    public function biaya_store(Request $request)
    {

        $cek = SettingBiaya::where('corporate_id', Session::get('corp_id'))->count();
        // dd($cek);
        if ($cek == 0) {
            SettingBiaya::create(
                [
                    'corporate_id' =>  Session::get('corp_id'),
                    'biaya_pasang' => $request->biaya_pasang,
                    'biaya_psb' => $request->biaya_psb,
                    'biaya_sales' => $request->biaya_sales,
                    'biaya_ppn' => $request->biaya_ppn,
                    'biaya_bph_uso' => $request->biaya_bph_uso,
                ]
            );
        } else {
            SettingBiaya::where('corporate_id', Session::get('corp_id'))->update(
                [
                    'biaya_pasang' => $request->biaya_pasang,
                    'biaya_psb' => $request->biaya_psb,
                    'biaya_sales' => $request->biaya_sales,
                    'biaya_ppn' => $request->biaya_ppn,
                    'biaya_bph_uso' => $request->biaya_bph_uso,
                ]
            );
        }
        $notifikasi = array(
            'pesan' => 'Menambah Biaya Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }

    // ==============================================END BIAYA==============================================
    // ==============================================START WAKTU TAGIHAN==============================================
    public function waktu_store(Request $request)
    {
        $cek = SettingWaktuTagihan::where('corporate_id', Session::get('corp_id'))->count();
        // dd($cek);
        if ($cek == 0) {
            SettingWaktuTagihan::create(
                [
                    'id' => '1',
                    'corporate_id' => Session::get('corp_id'),
                    'wt_jeda_isolir_hari' => $request->wt_jeda_isolir_hari,
                    'wt_jeda_tagihan_pertama' => $request->wt_jeda_tagihan_pertama,
                    'wt_tgl_isolir' => $request->wt_tgl_isolir,
                    ]
                );
            } else {
                SettingWaktuTagihan::where('corporate_id', Session::get('corp_id'))->update(
                    [
                    'wt_jeda_isolir_hari' => $request->wt_jeda_isolir_hari,
                    'wt_jeda_tagihan_pertama' => $request->wt_jeda_tagihan_pertama,
                    'wt_tgl_isolir' => $request->wt_tgl_isolir,
                ]
            );
        }
        $notifikasi = array(
            'pesan' => 'Menambah Akun Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }


    public function wa_getewai()
    {
        // $data['kendaraan'] = (new GlobalController)->data_kendaraan()->paginate(10);
        $wageteway = SettingWhatsapp::where('corporate_id', Session::get('corp_id'))->count();
            $data['data_whatsapp'] = SettingWhatsapp::where('corporate_id', Session::get('corp_id'))->get();
        // $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        return view('Applikasi/wa_getewai', $data);
    }
    public function store_wa_getewai(Request $request)
    {

         $request->validate([
             'wa_nama' => 'required|unique:setting_whatsapps,wa_nama',
             'wa_key' => 'required',
             'wa_url' => 'required',
             'wa_nomor' => 'required',
        ], [
            'wa_nama.required' => 'Agent tidak boleh kosong',
            'wa_nama.unique' => 'Agent sudah ada',
            'wa_key.required' => 'Key tidak boleh kosong',
            'wa_url.required' => 'URL tidak boleh kosong',
            'wa_nomor.required' => 'Nomor tidak boleh kosong',
        ]);

        $create['corporate_id'] = Session::get('corp_id');
        $create['wa_nama'] = $request->wa_nama;
        $create['wa_key'] = $request->wa_key;
        $create['wa_url'] = $request->wa_url;
        $create['wa_nomor'] = $request->wa_nomor;
        $create['wa_status'] = $request->wa_status;
        SettingWhatsapp::create($create);
        $notifikasi = array(
            'pesan' => 'Menambah Data Whatsapp Getewai Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.wa_getewai')->with($notifikasi);
    }
    public function update_wa_getewai(Request $request, $id)
    {
        $update['corporate_id'] = Session::get('corp_id');
        $update['wa_nama'] = $request->wa_nama;
        $update['wa_key'] = $request->wa_key;
        $update['wa_url'] = $request->wa_url;
        $update['wa_nomor'] = $request->wa_nomor;
        $update['wa_status'] = $request->wa_status;
        SettingWhatsapp::where('corporate_id', Session::get('corp_id'))->whereId($id)->update($update);
        $notifikasi = array(
            'pesan' => 'Menambah Data Whatsapp Getewai Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.wa_getewai')->with($notifikasi);
    }
    public function kendaraan()
    {
        $data['kendaraan'] = (new GlobalController)->data_kendaraan()->paginate(10);
        $data['permision'] = Permission::get();
        return view('transportasi/kendaraan', $data);
    }
    public function store_kendaraan(Request $request)
    {
        $create['trans_user_id'] = time();
        $create['trans_divisi_id'] = $request->trans_divisi_id;
        $create['trans_plat_nomor'] = $request->trans_plat_nomor;
        $create['trans_jenis_motor'] = $request->trans_jenis_motor;
        $create['trans_bensin'] = $request->trans_bensin;
        $create['trans_service'] = $request->trans_service;
        $create['trans_sewa'] = $request->trans_sewa;
        $create['trans_status'] = 'Enable';
        Kendaraan::create($create);
        $notifikasi = array(
            'pesan' => 'Menambah Data Kendaraan Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.kendaraan')->with($notifikasi);
    }
    public function update_kendaraan(Request $request, $id)
    {
        // dd($id);
        $create['trans_divisi_id'] = $request->trans_divisi_id;
        $create['trans_plat_nomor'] = $request->trans_plat_nomor;
        $create['trans_jenis_motor'] = $request->trans_jenis_motor;
        $create['trans_bensin'] = $request->trans_bensin;
        $create['trans_service'] = $request->trans_service;
        $create['trans_sewa'] = $request->trans_sewa;
        $create['trans_status'] = $request->trans_status;
        Kendaraan::whereId($id)->update($create);
        $notifikasi = array(
            'pesan' => 'Merubah Data Kendaraan Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.kendaraan')->with($notifikasi);
    }
    public function delete_kendaraan($id)
    {

        $cek = Kendaraan::whereId($id);
        if ($cek) {
            $cek->delete();
        }
        $notifikasi = array(
            'pesan' => 'Menghapus Data Kendaraan Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.kendaraan')->with($notifikasi);
    }

    public function site()
    {
        // dd('t');
        $count = Data_Site::where('corporate_id', Session::get('corp_id'))->count();

        if ($count == 0) {
            $data['id'] = 1;
        } else {
            $data['id'] = $count + 1;
        }

        $data['data_site'] = Data_Site::where('corporate_id', Session::get('corp_id'))->get();


        return view('Applikasi/site', $data);
    }
    public function site_store(Request $request)
    {
        $store_site['corporate_id'] = Session::get('corp_id');
        $store_site['id'] = $request->id;
        $store_site['site_nama'] = $request->site_nama;
        $store_site['site_prefix'] = $request->site_prefix;
        $store_site['site_status'] = 'Enable';
        
        Data_Site::create($store_site);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.site')->with($notifikasi);
    }
    public function update_site(Request $request, $id)
    {
        $store_site['site_nama'] = $request->site_nama;
        $store_site['site_prefix'] = $request->site_prefix;
        $store_site['site_status'] = $request->site_status;
        
        Data_Site::where('corporate_id', Session::get('corp_id'))->where('id', $id)->update($store_site);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.site')->with($notifikasi);
    }
    public function kelurahan()
    {
        $data['data_site'] = Data_Site::where('site_status','Enable')->where('corporate_id', Session::get('corp_id'))->get();
        $data['data_kelurahan'] = Data_Kelurahan::where('corporate_id', Session::get('corp_id'))->get();
        return view('Applikasi/kelurahan', $data);
    }
    public function kelurahan_store(Request $request)
    {
        $store['corporate_id'] = Session::get('corp_id');
        $store['kel_site_id'] = $request->kel_site_id;
        $store['kel_nama'] = $request->kel_nama;
        $store['kel_ket'] = $request->kel_ket;
        $store['kel_status'] = 'Enable';
        
        Data_Kelurahan::create($store);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Kelurahan',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.kelurahan')->with($notifikasi);
    }
    public function update_kelurahan(Request $request, $id)
    {
        $store['kel_site_id'] = $request->kel_site_id;
        $store['kel_nama'] = $request->kel_nama;
        $store['kel_ket'] = $request->kel_ket;
        $store['kel_status'] = $request->kel_status;
        
        Data_Kelurahan::where('kel_id', $id)->where('corporate_id',Session::get('corp_id'))->update($store);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Kelurahan',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.kelurahan')->with($notifikasi);
    }
    public function data_rt()
    {
        $data['data_kelurahan'] = Data_Kelurahan::where('corporate_id',Session::get('corp_id'))->where('kel_status','Enable')->get();
        $data['data_rt'] = Data_RT::join('data__kelurahans','data__kelurahans.kel_id','=','data__rts.rt_kel_id')
        ->join('data__sites','data__sites.site_id','=','data__kelurahans.kel_site_id')
        ->where('corporate_id',Session::get('corp_id'))
        ->get();
        return view('Applikasi/rt', $data);
    }
    //    'rt_id',
    //     'rt_kelurahan',
    //     'rt_nama',
    //     'rt_ket',
    //     'rt_status',
}
