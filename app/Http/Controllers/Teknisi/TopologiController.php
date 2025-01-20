<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Site;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Teknisi\Data_Odc;
use App\Models\Teknisi\Data_Odp;
use App\Models\Teknisi\Data_Olt;
use App\Models\Teknisi\Data_pop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TopologiController extends Controller
{
    public function pop()
    {

        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        $data['data_pop'] = Data_pop::join('data__sites', 'data__sites.site_id', '=', 'data_pops.pop_id_site')->get();

        return view('Teknisi/pop', $data);
    }

    public function pop_store(Request $request)
    {
        $query = Data_pop::query();
        $count1 = $query->count();
        $count2 = $query->where('pop_id_site', $request->pop_id_site)->count(); #sesuai site

        if ($count1 == 0) {
            $store_pop['pop_id'] = '1';
        } else {
            $store_pop['pop_id'] = $count1 + 1;
        }
        if ($count2 == 0) {
            $store_pop['pop_kode'] = $request->pop_id_site . '.' . '1';
        } else {
            $store_pop['pop_kode'] = $request->pop_id_site . '.' . $count2 + 1;
        }
        $store_pop['pop_id_site'] = $request->pop_id_site;
        $store_pop['pop_nama'] = $request->pop_nama;
        $store_pop['pop_alamat'] = $request->pop_alamat;
        $store_pop['pop_koordinat'] = $request->pop_koordinat;
        $store_pop['pop_ip1'] = $request->pop_ip1;
        $store_pop['pop_ip2'] = $request->pop_ip2;
        $store_pop['pop_keterangan'] = $request->pop_keterangan;
        $store_pop['pop_status'] = 'Enable';
        // dd($store_pop);
        $photo = $request->file('pop_topologi_img');
        $filename = $photo->getClientOriginalName();
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store_pop['pop_topologi_img'] = $filename;


        Data_pop::create($store_pop);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan POP',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.pop')->with($notifikasi);
    }
    public function update_pop(Request $request, $id)
    {

        $find = Data_pop::where('pop_id', $id)->first();
        $store_pop['pop_nama'] = $request->pop_nama;
        $store_pop['pop_alamat'] = $request->pop_alamat;
        $store_pop['pop_koordinat'] = $request->pop_koordinat;
        $store_pop['pop_ip1'] = $request->pop_ip1;
        $store_pop['pop_ip2'] = $request->pop_ip2;
        $store_pop['pop_keterangan'] = $request->pop_keterangan;
        $store_pop['pop_status'] = $request->pop_status;
        $photo = $request->file('pop_topologi_img');
        if ($photo) {
            $filename = $photo->getClientOriginalName();
            $path = 'topologi/' . $filename;
            if ($find->pop_topologi_img) {
                Storage::disk('public')->delete('topologi/' . $find->pop_topologi_img);
            }
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store_pop['pop_topologi_img'] = $filename;
        }

        Data_pop::where('pop_id', $id)->update($store_pop);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.pop')->with($notifikasi);
    }
    public function olt()
    {

        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        $data['data_barang'] = Data_Barang::where('barang_status', '0')->get();
        $data['data_pop'] = Data_pop::where('pop_status', 'Enable')->get();
        // $data['data_olt'] = Data_Site::join('data_pops', 'data_pops.pop_id_site', '=', 'data__sites.site_id')
        //     ->join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
        //     ->get();
        $data['data_olt'] = Data_Olt::join('data_pops', 'data_pops.pop_id', '=', 'data__olts.olt_id_pop')
            ->join('data__sites', 'data__sites.site_id', '=', 'data_pops.pop_id_site')
            ->get();


        return view('Teknisi/olt', $data);
    }



    public function olt_store(Request $request)
    {


        $data_pop = Data_Pop::where('pop_id', $request->olt_id_pop)->first();
        $query = Data_Olt::query();
        $count1 = $query->count();
        $count2 = $query->where('olt_id_pop', $request->olt_id_pop)->count(); #sesuai site

        if ($count1 == 0) {
            $store_olt['olt_id'] = '1';
        } else {
            $store_olt['olt_id'] = $count1 + 1;
        }
        if ($count2 == 0) {
            $store_olt['olt_kode'] = $data_pop->pop_kode . '.' . '1';
        } else {
            $store_olt['olt_kode'] = $data_pop->pop_kode . '.' . $count2 + 1;
        }

        $store_olt['olt_id_pop'] = $request->olt_id_pop;
        $store_olt['olt_nama'] = $request->olt_nama;
        $store_olt['olt_merek'] = $request->olt_merek;
        $store_olt['olt_mac'] = $request->olt_mac;
        $store_olt['olt_sn'] = $request->olt_sn;
        $store_olt['olt_pon'] = $request->olt_pon;
        $store_olt['olt_ip'] = $request->olt_ip;
        $store_olt['olt_username'] = $request->olt_username;
        $store_olt['olt_password'] = $request->olt_password;
        $store_olt['olt_ip_default'] = $request->olt_ip_default;
        $store_olt['olt_username_default'] = $request->olt_username_default;
        $store_olt['olt_password_default'] = $request->olt_password_default;
        $store_olt['olt_keterangan'] = $request->olt_keterangan;
        $store_olt['olt_status'] = 'Enable';
        // dd($store_olt);

        $photo = $request->file('olt_topologi_img');
        $filename = $photo->getClientOriginalName();
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store_olt['olt_topologi_img'] = $filename;

        Data_Olt::create($store_olt);
        // dd($store_olt['olt_id']);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan OLT',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.olt')->with($notifikasi);
    }

    public function update_olt(Request $request, $id)
    {
        $find = Data_Olt::where('olt_id', $id)->first();
        $store_olt['olt_nama'] = $request->olt_nama;
        $store_olt['olt_merek'] = $request->olt_merek;
        $store_olt['olt_mac'] = $request->olt_mac;
        $store_olt['olt_sn'] = $request->olt_sn;
        $store_olt['olt_pon'] = $request->olt_pon;
        $store_olt['olt_ip'] = $request->olt_ip;
        $store_olt['olt_username'] = $request->olt_username;
        $store_olt['olt_password'] = $request->olt_password;
        $store_olt['olt_ip_default'] = $request->olt_ip_default;
        $store_olt['olt_username_default'] = $request->olt_username_default;
        $store_olt['olt_password_default'] = $request->olt_password_default;
        $store_olt['olt_keterangan'] = $request->olt_keterangan;
        $store_olt['olt_status'] = $request->olt_status;
        // dd($store_pop);
        $photo = $request->file('olt_topologi_img');
        if ($photo) {
            $filename = $photo->getClientOriginalName();
            $path = 'topologi/' . $filename;
            if ($find->olt_topologi_img) {
                Storage::disk('public')->delete('topologi/' . $find->olt_topologi_img);
            }
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store_olt['olt_topologi_img'] = $filename;
        }

        Data_Olt::where('olt_id', $id)->update($store_olt);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.olt')->with($notifikasi);
    }


    public function odc()
    {

        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();

        $data['data_barang'] = Data_Barang::where('barang_status', '0')->get();
        $data['data_olt'] = Data_Olt::where('olt_status', 'Enable')->get();
        $data['data_odc'] = Data_Odc::join('data__olts', 'data__olts.olt_id', '=', 'data__odcs.odc_id_olt')
            ->join('data_pops', 'data_pops.pop_id', '=', 'data__olts.olt_id_pop')
            ->join('data__sites', 'data__sites.site_id', '=', 'data_pops.pop_id_site')
            ->get();

        return view('Teknisi/odc', $data);
    }

    public function odc_store(Request $request)
    {

        $data_olt = Data_Olt::where('olt_id', $request->odc_id_olt)->first();
        $query = Data_Odc::query();
        $count1 = $query->count();
        $count2 = $query->where('odc_id_olt', $request->odc_id_olt)->count(); #sesuai site

        if ($count1 == 0) {
            $store['odc_id'] = '1';
        } else {
            $store['odc_id'] = $count1 + 1;
        }
        if ($count2 == 0) {
            $store['odc_kode'] = $data_olt->olt_kode . '.' . '1';
        } else {
            $store['odc_kode'] = $data_olt->olt_kode . '.' . $count2 + 1;
        }

        $store['odc_id_olt'] = $request->odc_id_olt;
        $store['odc_pon_olt'] = $request->odc_pon_olt;
        $store['odc_core'] = $request->odc_core;
        $store['odc_nama'] = $request->odc_nama;
        $store['odc_jumlah_port'] = $request->odc_jumlah_port;
        $store['odc_koordinat'] = $request->odc_koordinat;
        $store['odc_keterangan'] = $request->odc_keterangan;
        $store['odc_status'] = 'Enable';

        // dd($store);

        $photo = $request->file('odc_topologi_img');
        $filename = $photo->getClientOriginalName();
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store['odc_topologi_img'] = $filename;

        $photo_2 = $request->file('odc_lokasi_img');
        $filename_2 = $photo_2->getClientOriginalName();
        $path_2 = 'topologi/' . $filename_2;
        Storage::disk('public')->put($path_2, file_get_contents($photo_2));
        $store['odc_lokasi_img'] = $filename_2;


        Data_Odc::create($store);
        // dd($store);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan ODC',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odc')->with($notifikasi);
    }

    public function update_odc(Request $request, $id)
    {
        $exp = explode(".", $request->odc_kode);
        $update_kode = $exp[0] . '.' . $exp[1] . '.' . $request->odc_id_olt . '.' . $exp[3];
        $find = Data_Odc::where('odc_id', $id)->first();
        $store['odc_kode'] = $update_kode;
        $store['odc_id_olt'] = $request->odc_id_olt;
        $store['odc_pon_olt'] = $request->odc_pon_olt;
        $store['odc_core'] = $request->odc_core;
        $store['odc_nama'] = $request->odc_nama;
        $store['odc_jumlah_port'] = $request->odc_jumlah_port;
        $store['odc_koordinat'] = $request->odc_koordinat;
        $store['odc_keterangan'] = $request->odc_keterangan;
        $store['odc_status'] = $request->odc_status;
        // dd($store_pop);
        $photo = $request->file('odc_topologi_img');
        if ($photo) {

            $filename = $photo->getClientOriginalName();
            $path = 'topologi/' . $filename;
            if ($find->odc_topologi_img) {
                Storage::disk('public')->delete('topologi/' . $find->odc_topologi_img);
            }
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store['odc_topologi_img'] = $filename;
        }
        $photo_2 = $request->file('odc_lokasi_img');
        if ($photo_2) {
            $filename_2 = $photo_2->getClientOriginalName();
            $path_2 = 'topologi/' . $filename_2;
            if ($find->odc_lokasi_img) {
                Storage::disk('public')->delete('topologi/' . $find->odc_lokasi_img);
            }
            Storage::disk('public')->put($path_2, file_get_contents($photo_2));
            $store['odc_lokasi_img'] = $filename_2;
        }

        Data_Odc::where('odc_id', $id)->update($store);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Odc',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odc')->with($notifikasi);
    }

    public function odp()
    {

        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        $data['data_olt'] = Data_Olt::where('olt_status', 'Enable')->get();
        $data['data_odc'] = Data_Odc::where('odc_status', 'Enable')->get();
        $data['data_odp'] = Data_Odp::join('data__odcs', 'data__odcs.odc_id', '=', 'data__odps.odp_id_odc')
            ->join('data__olts', 'data__olts.olt_id', '=', 'data__odcs.odc_id_olt')
            ->join('data_pops', 'data_pops.pop_id', '=', 'data__olts.olt_id_pop')
            ->join('data__sites', 'data__sites.site_id', '=', 'data_pops.pop_id_site')
            ->get();

        return view('Teknisi/odp', $data);
    }
    public function odp_store(Request $request)
    {

        $data_odc = Data_Odc::where('odc_id', $request->odp_id_odc)->first();
        $query = Data_Odp::query();
        $count1 = $query->count();
        $count2 = $query->where('odp_id', $request->odp_id_odc)->count(); #sesuai site

        if ($count1 == 0) {
            $store['odp_id'] = '1';
        } else {
            $store['odp_id'] = $count1 + 1;
        }
        if ($count2 == 0) {
            $store['odp_kode'] = $data_odc->odc_kode . '.' . '1';
        } else {
            $store['odp_kode'] = $data_odc->odc_kode . '.' . $count2 + 1;
        }
        $store['odp_id_odc'] = $request->odp_id_odc;
        $store['odp_port_odc'] = $request->odp_port_odc;
        $store['odp_opm_out_odc'] = $request->odp_opm_out_odc;
        $store['odp_opm_in'] = $request->odp_opm_in;
        $store['odp_opm_out'] = $request->odp_opm_out;
        $store['odp_core'] = $request->odp_core;
        $store['odp_nama'] = $request->odp_nama;
        $store['odp_jumlah_port'] = $request->odp_jumlah_port;
        $store['odp_koordinat'] = $request->odp_koordinat;
        $store['odp_keterangan'] = $request->odp_keterangan;
        $store['odp_status'] = $request->odp_status;

        // dd($store);


        $photo = $request->file('odp_topologi_img');
        $filename = $photo->getClientOriginalName();
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store['odp_topologi_img'] = $filename;

        $photo_2 = $request->file('odp_lokasi_img');
        $filename_2 = $photo_2->getClientOriginalName();
        $path_2 = 'topologi/' . $filename_2;
        Storage::disk('public')->put($path_2, file_get_contents($photo_2));
        $store['odp_lokasi_img'] = $filename_2;


        Data_Odp::create($store);
        // dd($store);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan ODP',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odp')->with($notifikasi);
    }

    public function update_odp() {}
}
