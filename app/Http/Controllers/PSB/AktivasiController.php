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
use App\Models\PSB\FtthInstalasi;
use App\Models\PSB\InputData;
use App\Models\Teknisi\Data_Odp;
use App\Models\Tiket\Data_Tiket;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;

class AktivasiController extends Controller
{
    public function aktivasi_psb($request, $query, $id,$team,$id_teknisi,$filename1)
    {


        
        $swaktu = SettingWaktuTagihan::where('corporate_id',Session::get('corp_id'))->first();
        $sbiaya = SettingBiaya::where('corporate_id',Session::get('corp_id'))->first();
        // $barang = Data_Barang::where('barang_id', $request->reg_kode_dropcore)->first();


        $tagihan_tanpa_ppn = $query->reg_harga + $query->reg_kode_unik;

        #FORMAT TANGGAL
        $tanggal = Carbon::now()->toDateString();
        $now = Carbon::now();
        $m = $now->format('m');
        $y = $now->format('Y');
        $d = $now->format('d');

        #PASCABAYAR SESUAI TGL PASANG (SIKLUS TETAP)
        $tag_pascabayar = Carbon::create(date('Y-m-'.$d))->addMonth(1)->toDateString();
        $inv_tgl_tagih_pascabayar = Carbon::create($tag_pascabayar)->addDay(-$swaktu->wt_jeda_tagihan_pertama)->toDateString();
        $inv_tgl_isolir_pascabayar = Carbon::create($tag_pascabayar)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

        #PASCABAYAR SESUAI TGL YANG DI TENTUKAN (SIKLUS BULAN)

        $tag_free3bln = Carbon::create($tanggal)->addMonth(3)->toDateString();
        $tag_free1th = Carbon::create($tanggal)->addMonth(12)->toDateString();

         $cek_hari_pasang = date('d', strtotime($tanggal));
        if ($cek_hari_pasang >= 25) {
            // $tanggal_pasang = date('Y-m-d', strtotime(Carbon::create(date($y . '-' . $m . '-01'))->addMonth(1)->toDateString()));
            // $tanggal_pasang_free = date('Y-m-d', strtotime(Carbon::create(date($y . '-' . $m . '-01'))->addMonth(1)->toDateString()));
            $inv_tgl_isolir1blan = Carbon::create($tanggal)->toDateString();
            $inv_tgl_isolir3blan = Carbon::create($tag_free3bln)->toDateString();
            $inv_tgl_isolir12blan = Carbon::create($tag_free1th)->toDateString();
        } else {
            // $tanggal_pasang = $tanggal;
            // $tanggal_pasang_free = date('Y-m-d', strtotime(Carbon::create(date($y . '-' . $m . '-01'))->toDateString()));
            $inv_tgl_isolir1blan = Carbon::create($tanggal)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir3blan = Carbon::create($tag_free3bln)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir12blan = Carbon::create($tag_free1th)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
        }

      

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
            $sub_inv['subinvoice_bph_uso'] = $query->reg_bph_uso;
            $sub_inv['subinvoice_total'] = $inv['inv_total'];
            $sub_inv['subinvoice_qty'] = '12';

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
             $sub_inv['subinvoice_bph_uso'] = $query->reg_bph_uso;
            $sub_inv['subinvoice_total'] = $inv['inv_total'];
            $sub_inv['subinvoice_qty'] = '1';

            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
        } else if ($query->reg_jenis_tagihan == 'PASCABAYAR') {
            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
            $pelanggan['reg_tgl_jatuh_tempo'] = $tag_pascabayar;
            $pelanggan['reg_tgl_tagih'] = $inv_tgl_tagih_pascabayar;
            $pelanggan['reg_status'] = 'PAID';
        } 
        
        $pelanggan['reg_progres'] = '3';
        $pelanggan['reg_tgl_pasang'] = Carbon::now()->toDateString();
        $pelanggan['reg_img'] = $filename1;


        $teknisi['corporate_id'] = Session::get('corp_id');
        $teknisi['user_id'] = $id_teknisi;
        $teknisi['teknisi_team'] = $team;
        $teknisi['teknisi_job'] = 'PSB';
        $teknisi['teknisi_ket'] =  $query->input_nama;
        $teknisi['teknisi_idpel'] =  $id;
        $teknisi['teknisi_status'] =  1;
        $update_input['input_koordinat'] = $request->input_koordinat;

        $tiket['tiket_teknisi1'] = $id_teknisi;
        $tiket['tiket_teknisi2'] = $request->teknisi2;
        $tiket['tiket_foto'] = $filename1;
        $tiket['tiket_status'] = 'Closed';
        Data_Tiket::where('corporate_id',Session::get('corp_id'))->where('tiket_idpel', $query->reg_idpel)->where('tiket_status', 'Aktivasi')->update($tiket);

        if ($query->reg_jenis_tagihan != 'PASCABAYAR') {
        // $inv['inv_tgl_pasang'] = Carbon::now()->toDateString();
        $inv['corporate_id'] = Session::get('corp_id');
        $inv['inv_status'] = 'UNPAID';
        $inv['inv_idpel'] = $query->reg_idpel;
        $inv['inv_nolayanan'] = $query->reg_nolayanan;
        $inv['inv_diskon'] = '0';
        $inv['inv_note'] = $query->input_nama;
        
        $sub_inv['corporate_id'] = Session::get('corp_id');
        $sub_inv['subinvoice_deskripsi'] = $query->paket_nama;
        $sub_inv['subinvoice_status'] = '0';

        $sub_sales['corporate_id'] = Session::get('corp_id');

        // if ($cek_hari_pasang < 25) {
            $cek_inv = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->first();
            if ($cek_inv) {
                $inv['inv_id'] = $cek_inv->inv_id;
                $sub_inv['subinvoice_id'] = $inv['inv_id'];
                Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->update($inv);
                SubInvoice::where('corporate_id',Session::get('corp_id'))->where('subinvoice_id', $sub_inv['subinvoice_id'])->update($sub_inv);
            } else {
                $inv['inv_id'] = (new GlobalController)->no_inv();
                $sub_inv['subinvoice_id'] = $inv['inv_id'];
                Invoice::create($inv);
                SubInvoice::create($sub_inv);
            }
            // } 
        } 
        
        
        
        
        Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_idpel', $id)->update($pelanggan);
        InputData::where('corporate_id',Session::get('corp_id'))->where('id', $id)->update($update_input);
        Teknisi::create($teknisi);
        // dd($sub_inv);
    }
}
