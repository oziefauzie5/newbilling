<?php

namespace App\Http\Controllers\Applikasi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    public function index()
    {
        $SettingTripay = SettingTripay::whereId('1')->first();
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
        $SettingAplikasi = SettingAplikasi::first();

        if (isset($SettingAplikasi) == NULL) {
            $data['app_nama'] = " ";
            $data['app_brand'] = " ";
            $data['app_alamat'] = " ";
            $data['app_npwp'] = "";
            $data['app_clientid'] = "";
            $data['app_logo'] = "";
            $data['app_favicon'] = "";
            $data['app_link_admin'] = "";
            $data['app_link_pelanggan'] = "";
        } else {
            $data['app_nama'] = $SettingAplikasi->app_nama;
            $data['app_brand'] = $SettingAplikasi->app_brand;
            $data['app_alamat'] = $SettingAplikasi->app_alamat;
            $data['app_clientid'] = $SettingAplikasi->app_clientid;
            $data['app_npwp'] = $SettingAplikasi->app_npwp;
            $data['app_logo'] = $SettingAplikasi->app_logo;
            $data['app_favicon'] = $SettingAplikasi->app_favicon;
            $data['app_link_admin'] = $SettingAplikasi->app_link_admin;
            $data['app_link_pelanggan'] = $SettingAplikasi->app_link_pelanggan;
        }
        $SettingBiaya = SettingBiaya::first();

        if (isset($SettingBiaya) == NULL) {
            $data['biaya_ppn'] = "0";
            $data['biaya_deposit'] = "0";
            $data['biaya_sales'] = "0";
            $data['biaya_sales_continue'] = "0";
            $data['biaya_psb'] = "0";
            $data['biaya_pasang'] = "0";
            $data['biaya_kas'] = "0";
            $data['biaya_kerjasama'] = "0";
        } else {
            $data['biaya_ppn'] = $SettingBiaya->biaya_ppn;
            $data['biaya_deposit'] = $SettingBiaya->biaya_deposit;
            $data['biaya_sales'] = $SettingBiaya->biaya_sales;
            $data['biaya_sales_continue'] = $SettingBiaya->biaya_sales_continue;
            $data['biaya_psb'] = $SettingBiaya->biaya_psb;
            $data['biaya_pasang'] = $SettingBiaya->biaya_pasang;
            $data['biaya_kas'] = $SettingBiaya->biaya_kas;
            $data['biaya_kerjasama'] = $SettingBiaya->biaya_kerjasama;
        }
        $SettingBiaya = SettingWaktuTagihan::first();

        if (isset($SettingBiaya) == NULL) {
            $data['wt_jeda_isolir_hari'] = "0";
            $data['wt_jeda_tagihan_pertama'] = "0";
        } else {
            $data['wt_jeda_isolir_hari'] = $SettingBiaya->wt_jeda_isolir_hari;
            $data['wt_jeda_tagihan_pertama'] = $SettingBiaya->wt_jeda_tagihan_pertama;
        }


        $data['SettingAkun'] =  (new SettingAkun())->SettingAkun()->get();
        // dd($data['SettingAkun']);
        $count = SettingAkun::count();
        if ($count == 0) {
            $data['akun_id'] = $Settingakun_id = 1;
        } else {
            $data['akun_id'] = $Settingakun_id = $count + 1;
        }
        return view('Applikasi/index', $data);
    }
    public function tripay_store(Request $request)
    {
        SettingTripay::updateOrCreate(
            [
                'id' => '1',
            ],
            [
                'tripay_merchant' => 'Tripay',
                'tripay_kode_merchant' => $request->tripay_kode_merchant,
                'tripay_url_callback' => $request->tripay_url_callback,
                'tripay_apikey' => $request->tripay_apikey,
                'tripay_privatekey' => $request->tripay_privatekey,
                'tripay_admin_topup' => $request->tripay_admin_topup,
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

        if ($request->akun_id == '1') {
            $tripay['id'] = '1';
            $tripay['akun_id'] = '1';
            $tripay['akun_nama'] = 'TRIPAY';
            $tripay['akun_status'] = 'Enable';
            $tripay['akun_kategori'] = 'PEMBAYARAN';

            $tunai['id'] = '2';
            $tunai['akun_id'] = '2';
            $tunai['akun_nama'] = 'TUNAI';
            $tunai['akun_status'] = 'Enable';
            $tunai['akun_kategori'] = 'PEMBAYARAN';

            $akun['id'] = '3';
            $akun['akun_id'] = '3';
            $akun['akun_nama'] = $request->nama_akun;
            $akun['akun_rekening'] = $request->akun_rekening;
            $akun['akun_pemilik'] = $request->nama_pemilik;
            $akun['akun_status'] = 'Enable';
            $akun['akun_kategori'] = $request->akun_kategori;

            SettingAkun::create($tripay);
            SettingAkun::create($tunai);
            SettingAkun::create($akun);
        } else {
            SettingAkun::create(
                [
                    'id' => $request->akun_id,
                    'akun_id' => $request->akun_id,
                    'akun_nama' => $request->nama_akun,
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
        SettingAkun::whereId($id)->update(
            [
                'akun_id' => $request->akun_id,
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
        $data = SettingAkun::find($id);
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
        $cek = SettingAplikasi::count();
        if ($cek == 0) {

            SettingAplikasi::create(
                [
                    'id' => '1',
                    'app_nama' => $request->app_nama,
                    'app_brand' => $request->app_brand,
                    'app_alamat' => $request->app_alamat,
                    'app_npwp' => $request->app_npwp,
                    'app_clientid' => $request->app_clientid,
                    'app_logo' => $filename1,
                    'app_favicon' => $filename2,
                    'app_link_admin' => $request->app_link_admin,
                    'app_link_pelanggan' => $request->app_link_pelanggan,
                ]
            );
        } else {
            SettingAplikasi::whereId('1')->update(
                [
                    'app_nama' => $request->app_nama,
                    'app_brand' => $request->app_brand,
                    'app_alamat' => $request->app_alamat,
                    'app_npwp' => $request->app_npwp,
                    'app_clientid' => $request->app_clientid,
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

        $cek = SettingBiaya::count();
        // dd($cek);
        if ($cek == 0) {
            SettingBiaya::create(
                [
                    'id' => '1',
                    'biaya_pasang' => $request->biaya_pasang,
                    'biaya_psb' => $request->biaya_psb,
                    'biaya_sales' => $request->biaya_sales,
                    'biaya_sales_continue' => $request->biaya_sales_continue,
                    'biaya_deposit' => $request->biaya_deposit,
                    'biaya_ppn' => $request->biaya_ppn,
                    'biaya_kas' => $request->biaya_kas,
                    'biaya_kerjasama' => $request->biaya_kerjasama,
                ]
            );
        } else {
            SettingBiaya::whereId('1')->update(
                [
                    'biaya_pasang' => $request->biaya_pasang,
                    'biaya_psb' => $request->biaya_psb,
                    'biaya_sales' => $request->biaya_sales,
                    'biaya_sales_continue' => $request->biaya_sales_continue,
                    'biaya_deposit' => $request->biaya_deposit,
                    'biaya_ppn' => $request->biaya_ppn,
                    'biaya_kas' => $request->biaya_kas,
                    'biaya_kerjasama' => $request->biaya_kerjasama,
                ]
            );
        }
        $notifikasi = array(
            'pesan' => 'Menambah Akun Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.index')->with($notifikasi);
    }

    // ==============================================END BIAYA==============================================
    // ==============================================START WAKTU TAGIHAN==============================================
    public function waktu_store(Request $request)
    {
        $cek = SettingWaktuTagihan::count();
        // dd($cek);
        if ($cek == 0) {
            SettingWaktuTagihan::create(
                [
                    'id' => '1',
                    'wt_jeda_isolir_hari' => $request->wt_jeda_isolir_hari,
                    'wt_jeda_tagihan_pertama' => $request->wt_jeda_tagihan_pertama,
                ]
            );
        } else {
            SettingWaktuTagihan::whereId('1')->update(
                [
                    'wt_jeda_isolir_hari' => $request->wt_jeda_isolir_hari,
                    'wt_jeda_tagihan_pertama' => $request->wt_jeda_tagihan_pertama,
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
        $wageteway = SettingWhatsapp::count();
        if ($wageteway > 0) {
            $data['data_whatsapp'] = SettingWhatsapp::join('data__sites', 'data__sites.site_id', '=', 'setting_whatsapps.wa_site')
                ->get();
        } else {
            $data['data_whatsapp'] = SettingWhatsapp::get();
        }
        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        return view('Applikasi/wa_getewai', $data);
    }
    public function store_wa_getewai(Request $request)
    {
        $create['wa_site'] = $request->wa_site;
        $create['wa_nama'] = $request->wa_nama;
        $create['wa_key'] = $request->wa_key;
        $create['wa_url'] = $request->wa_url;
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
        $update['wa_site'] = $request->wa_site;
        $update['wa_nama'] = $request->wa_nama;
        $update['wa_key'] = $request->wa_key;
        $update['wa_url'] = $request->wa_url;
        $update['wa_status'] = $request->wa_status;
        SettingWhatsapp::whereId($id)->update($update);
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
        $count = Data_Site::count();

        if ($count == 0) {
            $data['site_id'] = 1;
        } else {
            $data['site_id'] = $count + 1;
        }
        // $data['site_id'] = '1' . sprintf("%03d", $idsite);

        $data['data_site'] = Data_Site::get();


        return view('Applikasi/site', $data);
    }
    public function site_store(Request $request)
    {
        $store_site['site_id'] = $request->site_id;
        $store_site['site_nama'] = $request->site_nama;
        $store_site['site_prefix'] = $request->site_prefix;
        $store_site['site_brand'] = $request->site_brand;
        $store_site['site_keterangan'] = $request->site_keterangan;
        $store_site['site_status'] = 'Disable';

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
        $store_site['site_brand'] = $request->site_brand;
        $store_site['site_keterangan'] = $request->site_keterangan;
        $store_site['site_status'] = $request->site_status;

        Data_Site::where('site_id', $id)->update($store_site);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.app.site')->with($notifikasi);
    }
}
