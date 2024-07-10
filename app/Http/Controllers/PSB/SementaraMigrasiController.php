<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SementaraMigrasiController extends Controller
{

    public function sementara_migrasi()
    {
        $data['input_data'] = InputData::where('input_status', 'MIGRASI')->get();
        $data['data_router'] = Router::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();
        return view('Registrasi/sementara_migrasi', $data);
    }
    public function store_sementara_migrasi(Request $request)
    {

        $router_nama = Router::whereId($request->reg_router)->first();
        $paket_nama = Paket::where('paket_id', $request->reg_profile)->first();
        $tanggal = Carbon::now()->toDateString();
        $tgl_tagih = Carbon::create($request->tgl_jttempo)->addDay(-1)->toDateString();

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
        Session::flash('tgl_aktif', $request->tgl_aktif);
        Session::flash('tgl_jttempo', $request->tgl_jttempo);

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
            'reg_jenis_tagihan' => 'required',
            'reg_harga' => 'required',
            'input_subseles' => 'required',
            'reg_profile' => 'required',
            'tgl_jttempo' => 'required',
            'tgl_aktif' => 'required',
            'reg_tgl' => 'required',
        ], [
            'tgl_jttempo.required' => 'Tanggal jatuh tempo tidak boleh kosong',
            'tgl_aktif.required' => 'Tanggal aktif tidak boleh kosong',
            'reg_tgl.required' => 'Tanggal registrasi tidak boleh kosong',
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
        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
        $data['reg_harga'] = $request->reg_harga;
        $data['reg_ppn'] = $request->reg_ppn;
        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
        $data['reg_kode_unik'] = $request->reg_kode_unik;
        $data['reg_dana_kas'] = $request->reg_dana_kas;
        $data['reg_catatan'] = $request->reg_catatan;
        $data['reg_profile'] = $request->reg_profile;
        $data['reg_tgl_jatuh_tempo'] = $request->tgl_jttempo;
        $data['reg_tgl_pasang'] = $request->tgl_aktif;
        $data['reg_tgl_tagih'] = $tgl_tagih;
        $data['reg_status'] = 'MIGRASI';
        $data['reg_progres'] = '5';
        $update['input_tgl'] = $request->reg_tgl;
        $update['input_maps'] =  $request->maps;
        $update['input_status'] =  'REGIST';




        // dd($request->reg_idpel);
        $regist = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.id', $request->reg_idpel)->first();
        $router = Router::whereId($data['reg_router'])->first();
        // $tgl_aktif = date('d/m/Y', strtotime($regist->created_at));
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($request->reg_layanan == 'PPP') {
            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ppp/secret/add', [
                    'name' => $data['reg_username'] == '' ? '' : $data['reg_username'],
                    'password' => $data['reg_password'] == '' ? '' : $data['reg_password'],
                    'service' => 'pppoe',
                    'profile' => $paket_nama->paket_nama  == '' ? 'default' : $paket_nama->paket_nama,
                    'comment' => 'MIGRASI',
                ]);
                Registrasi::create($data);
                InputData::where('id', $request->reg_idpel)->update($update);
                $notifikasi = array(
                    'pesan' => 'Berhasil menambahkan pelanggan',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index')->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambahkan pelanggan',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.index')->with($notifikasi);
            }
        } elseif ($request->reg_layanan == 'HOTSPOT') {


            if ($API->connect($ip, $user, $pass)) {
                $API->comm('/ip/hotspot/user/add', [
                    'name' => $data['reg_username'] == '' ? '' : $data['reg_username'],
                    'password' => $data['reg_password']   == '' ? '' : $data['reg_password'],
                    'profile' => $paket_nama->paket_nama  == '' ? 'default' : $paket_nama->paket_nama,
                    'comment' => $request->reg_nama  == '' ? '' : $request->reg_nama,
                    'disabled' => 'yes',
                ]);
                // dd($request->reg_nama);
                Registrasi::create($data);
                InputData::where('id', $request->reg_idpel)->update($update);

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



        return redirect()->route('admin.reg.registrasi_api_sementara', ['id' => $data['reg_idpel']]);
    }

    public function proses_aktivasi_sementara(Request $request, $id)
    {

        $swaktu = SettingWaktuTagihan::first();
        $sbiaya = SettingBiaya::first();
        $barang = SubBarang::where('id_subbarang', $request->kode)->first();
        if ($barang->subbarang_stok > $request->after) {


            $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
                ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
                ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                ->where('registrasis.reg_idpel', $id)
                ->first();
            $tagihan_tanpa_ppn = $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik;

            #FORMAT TANGGAL
            $tanggal = Carbon::now()->toDateString();
            $tag_pascabayar = Carbon::create($tanggal)->addMonth(1)->toDateString();
            $tag_free3bln = Carbon::create($tanggal)->addMonth(3)->toDateString();
            $tag_free1th = Carbon::create($tanggal)->addMonth(12)->toDateString();
            $inv_tgl_tagih_pascabayar = Carbon::create($tag_pascabayar)->addDay(-$swaktu->wt_jeda_tagihan_pertama)->toDateString();
            $inv_tgl_isolir_pascabayar = Carbon::create($tag_pascabayar)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

            $inv_tgl_isolir1blan = Carbon::create($tanggal)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir3blan = Carbon::create($tag_free3bln)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir12blan = Carbon::create($tag_free1th)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

            $periode3blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(3)->toDateString();
            $periode12blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(12)->toDateString();
            $periode1blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(1)->toDateString();

            #API MIKROTIK
            $ip =   $query->router_ip . ':' . $query->router_port_api;
            $user = $query->router_username;
            $pass = $query->router_password;
            $API = new RouterosAPI();
            $API->debug = false;
            if ($API->connect($ip, $user, $pass)) {
                $secret = $API->comm('/ppp/secret/print', [
                    '?name' => $query->reg_username,
                ]);
                if ($secret) {
                    $API->comm('/ppp/secret/set', [
                        '.id' => $secret[0]['.id'],
                        'disabled' => 'no',
                    ]);

                    if ($query->reg_jenis_tagihan == 'FREE') {
                        $teknisi['teknisi_psb'] = '0';
                        $inv['inv_tgl_isolir'] = $inv_tgl_isolir12blan;
                        $inv['inv_total'] = $tagihan_tanpa_ppn * 12 + ($query->reg_ppn * 12);
                        $inv['inv_tgl_tagih'] = $tanggal;
                        $inv['inv_tgl_jatuh_tempo'] = $tag_free1th;
                        $inv['inv_periode'] = $periode12blan;

                        $sub_inv['subinvoice_harga'] = $query->reg_harga;
                        $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                        $sub_inv['subinvoice_total'] = $inv['inv_total'];
                        $sub_inv['subinvoice_qty'] = '12';

                        $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                        $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                    } else if ($query->reg_jenis_tagihan == 'FREE 3 BULAN') {
                        $teknisi['teknisi_psb'] = '0';
                        $inv['inv_tgl_isolir'] = $inv_tgl_isolir3blan;
                        $inv['inv_total'] = $tagihan_tanpa_ppn * 3 + ($query->reg_ppn * 3);
                        $inv['inv_tgl_tagih'] = $tanggal;
                        $inv['inv_tgl_jatuh_tempo'] = $tag_free3bln;
                        $inv['inv_periode'] = $periode3blan;

                        $sub_inv['subinvoice_harga'] = $query->reg_harga;
                        $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                        $sub_inv['subinvoice_total'] = $inv['inv_total'];
                        $sub_inv['subinvoice_qty'] = '3';

                        $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                        $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                    } else if ($query->reg_jenis_tagihan == 'PRABAYAR') {
                        $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                        $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                        $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                        $inv['inv_tgl_tagih'] = $tanggal;
                        $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                        $inv['inv_periode'] = $periode1blan;

                        $sub_inv['subinvoice_harga'] = $query->reg_harga;
                        $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                        $sub_inv['subinvoice_total'] = $inv['inv_total'];
                        $sub_inv['subinvoice_qty'] = '1';

                        $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                        $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                    } else if ($query->reg_jenis_tagihan == 'PASCABAYAR') {
                        $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                        $inv['inv_tgl_isolir'] = $inv_tgl_isolir_pascabayar;
                        $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                        $inv['inv_tgl_tagih'] = $inv_tgl_tagih_pascabayar;
                        $inv['inv_tgl_jatuh_tempo'] = $tag_pascabayar;
                        $inv['inv_periode'] = $periode1blan;

                        $sub_inv['subinvoice_harga'] = $query->reg_harga;
                        $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                        $sub_inv['subinvoice_total'] = $inv['inv_total'];
                        $sub_inv['subinvoice_qty'] = '1';

                        $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                        $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                    } else if ($query->reg_jenis_tagihan == 'DEPOSIT') {
                        $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                        $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                        $inv['inv_total'] = $query->reg_deposit;
                        $inv['inv_tgl_tagih'] = $tanggal;
                        $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                        $inv['inv_periode'] = $periode1blan;

                        $sub_inv['subinvoice_harga'] = $inv['inv_total'];
                        $sub_inv['subinvoice_ppn'] = '0';
                        $sub_inv['subinvoice_total'] = $inv['inv_total'];
                        $sub_inv['subinvoice_qty'] = '1';

                        $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                        $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        $pelanggan['reg_deposit'] = $inv['inv_total'];
                    }

                    $update_barang['subbarang_keluar'] = $barang->subbarang_keluar + $request->total;
                    $update_barang['subbarang_stok'] = $barang->subbarang_stok - $request->total;

                    $pelanggan['reg_progres'] = '2';
                    $pelanggan['reg_tgl_pasang'] = $tanggal;

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
                    Registrasi::where('reg_idpel', $id)->update($pelanggan);
                    SubBarang::where('id_subbarang', $request->kode)->update($update_barang);

                    $notifikasi = array(
                        'pesan' => 'Aktivasi Berhasil ',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.teknisi.index')->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Router Discconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
            }
        }
    }
}
