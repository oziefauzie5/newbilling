<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\PSB\Registrasi;
use App\Models\Teknisi\Teknisi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\PSB\InputData;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;

class AktivasiController extends Controller
{
    public function aktivasi_psb($request, $query, $id)
    {




        $user = (new GlobalController)->user_admin();
        $noc = $user['user_id'];
        $noc_nama = $user['user_nama'];

        // dd($no_sk);


        $explode1 = explode("|", $request->teknisi1);
        $team = $explode1[1] . ' & ' . ucwords($request->teknisi2);

        $id_teknisi = $explode1[0];
        $teknisi_nama = $explode1[1];
        $swaktu = SettingWaktuTagihan::first();
        $sbiaya = SettingBiaya::first();
        $barang = Data_Barang::where('barang_id', $request->reg_kode_dropcore)->first();


        $tagihan_tanpa_ppn = $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik;

        #FORMAT TANGGAL
        $y = date('y');
        $m = date('m');
        $tanggal = Carbon::now()->toDateString();
        $cek_hari = date('d', strtotime($tanggal));
        // if ($cek_hari >= 25) {

        // }

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



        if ($query->reg_jenis_tagihan == 'FREE') {
            $teknisi['teknisi_psb'] = '0';
            $inv['inv_tgl_isolir'] = $inv_tgl_isolir12blan;
            $inv['inv_total'] = $tagihan_tanpa_ppn * 12 + ($query->reg_ppn * 12);
            $inv['inv_tgl_tagih'] = $tanggal;
            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
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
            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
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
        // dd($barang->barang_digunakan + $request->reg_penggunaan_dropcore);
        if ($barang->barang_digunakan + $request->reg_penggunaan_dropcore == 0) {
            $barang_status = 1;
        } else {
            $barang_status = 0;
        }


        $update_barang['barang_status'] =  $barang_status;
        $update_barang['barang_digunakan'] =  $request->reg_terpakai + $request->reg_penggunaan_dropcore;
        $update_barang['barang_nama_pengguna'] = 'PSB ' . $query->input_nama;

        $photo = $request->file('reg_img');
        $bk_name = $query->input_nama . '.jpg';
        $path = 'barang_keluar/' . $bk_name;
        Storage::disk('public')->put($path, file_get_contents($photo));

        $create_barang['bk_id'] = $request->reg_skb;
        $create_barang['bk_jenis_laporan'] = 'Instalasi PSB';
        $create_barang['bk_id_barang'] = $barang->barang_id;
        $create_barang['bk_id_tiket'] = '0';
        $create_barang['bk_kategori'] = $barang->barang_kategori;
        $create_barang['bk_before'] = $request->reg_before;
        $create_barang['bk_after'] = $request->reg_after;
        $create_barang['bk_terpakai'] = $request->reg_terpakai + $request->reg_penggunaan_dropcore;
        $create_barang['bk_jumlah'] = $request->reg_penggunaan_dropcore;
        $create_barang['bk_keperluan'] = 'PSB ' . $query->input_nama;
        $create_barang['bk_file_bukti'] = $bk_name;
        $create_barang['bk_nama_penggunan'] = $query->input_nama;
        $create_barang['bk_waktu_keluar'] = date('Y-m-d H:m:s', strtotime(Carbon::now()));
        $create_barang['bk_admin_input'] = $noc_nama;
        $create_barang['bk_penerima'] = $teknisi_nama;
        $create_barang['bk_status'] = 1;
        $create_barang['bk_keterangan'] = $barang->barang_ket;
        $create_barang['bk_harga'] = $barang->barang_harga_satuan * $barang->barang_qty;

        // dd($update_barang);
        $pelanggan['reg_progres'] = '3';


        $pelanggan['reg_site'] = $request->reg_site;
        $pelanggan['reg_pop'] = $request->reg_pop;
        $pelanggan['reg_router'] = $request->reg_router;
        $pelanggan['reg_olt'] = $request->reg_olt;
        $pelanggan['reg_odc'] = $request->reg_odc;
        $pelanggan['reg_odp'] = $request->reg_odp;
        $pelanggan['reg_mac_olt'] = $request->reg_mac_olt;
        $pelanggan['reg_in_ont'] = $request->reg_in_ont;
        $pelanggan['reg_onuid'] = $request->reg_onuid;
        $pelanggan['reg_slot_odp'] = $request->reg_slot_odp;
        $pelanggan['reg_koodinat_odp'] = $request->reg_koodinat_odp;
        $pelanggan['reg_teknisi_team'] = $team;
        $pelanggan['reg_tgl_pasang'] = $tanggal;
        // $photo = $request->file('reg_img');
        // $filename = $photo->getClientOriginalName();
        $filename = $query->input_nama . '.jpg';
        $path = 'rumah_pelanggan/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $pelanggan['reg_img'] = $filename;

        $photo_2 = $request->file('reg_foto_odp');
        $filename_2 = 'ODP' . $query->input_nama . '.jpg';
        // $filename_2 = $photo_2->getClientOriginalName();
        $path_2 = 'odp_pelanggan/' . $filename_2;
        Storage::disk('public')->put($path_2, file_get_contents($photo_2));
        $pelanggan['reg_foto_odp'] = $filename_2;


        $teknisi['teknisi_userid'] = $id_teknisi;
        $teknisi['teknisi_team'] = $team;
        $teknisi['teknisi_job'] = 'PSB';
        $teknisi['teknisi_ket'] =  $query->input_nama;
        $teknisi['teknisi_idpel'] =  $id;
        $teknisi['teknisi_noc_userid'] =  $noc;
        $teknisi['teknisi_status'] =  1;
        $update_input['input_koordinat'] = $request->input_koordinat;

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


        Registrasi::where('reg_idpel', $id)->update($pelanggan);
        InputData::where('id', $id)->update($update_input);
        Teknisi::where('teknisi_idpel', $id)->where('teknisi_userid', $id_teknisi)->create($teknisi);

        Data_Barang::where('barang_id', $barang->barang_id)->update($update_barang);
        Data_BarangKeluar::create($create_barang);
    }
}
