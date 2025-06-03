<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Site;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Router\Router;
use App\Models\Teknisi\Data_Odc;
use App\Models\Teknisi\Data_Odp;
use App\Models\Teknisi\Data_Olt;
use App\Models\Teknisi\Data_OltSub;
use App\Models\Teknisi\Data_pop;
use App\Models\Teknisi\RouterSub;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TopologiController extends Controller
{
    public function pop()
    {
        $data['data_site'] = Data_Site::where('corporate_id',Session::get('corp_id'))->where('site_status', 'Enable')->get();
        $data['data_pop'] = Data_pop::where('corporate_id',Session::get('corp_id'))->with('pop_site')->get();

        return view('Teknisi/pop', $data);
    }

    public function pop_store(Request $request)
    {
        Session::flash('pop_nama', $request->pop_nama); #
        Session::flash('pop_alamat', $request->pop_alamat); #
        Session::flash('pop_koordinat', $request->pop_koordinat); #
        Session::flash('data__site_id', $request->data__site_id); #
         $request->validate([
            'pop_nama' => 'required',
            'pop_alamat' => 'required',
            'pop_koordinat' => 'required',
            'data__site_id' => 'required',
            'pop_file_topologi' => 'required|max:1000|mimes:pdf',
       
        ], [
            'pop_nama.required' => 'Nama tidak boleh kosong',
            'pop_alamat.required' => 'Alamat tidak boleh kosong',
            'pop_koordinat.required' => 'Koordinat tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'pop_file_topologi.required' => 'File tidak boleh kosong',
            'pop_file_topologi.max' => 'Ukuran File terlalu besar',
            'pop_file_topologi.mimes' => 'Format hanya bisa pdf',
        ]);
        
        $store_pop['corporate_id'] = Session::get('corp_id');
        $store_pop['data__site_id'] = $request->data__site_id;
        $store_pop['pop_nama'] = strtoupper($request->pop_nama);
        $store_pop['pop_alamat'] = strtoupper($request->pop_alamat);
        $store_pop['pop_koordinat'] = $request->pop_koordinat;
        $store_pop['pop_status'] = 'Enable';
        
        $photo = $request->file('pop_file_topologi');
        $filename = Session::get('corp_id').'_'.$store_pop['pop_nama'].'.pdf';
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store_pop['pop_file_topologi'] = $filename;


        Data_pop::create($store_pop);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan POP',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.pop')->with($notifikasi);
    }
    public function update_pop(Request $request, $id)
    {
        Session::flash('pop_nama', $request->pop_nama); #
        Session::flash('pop_alamat', $request->pop_alamat); #
        Session::flash('pop_koordinat', $request->pop_koordinat); #
        Session::flash('data__site_id', $request->data__site_id); #
         $request->validate([
            'pop_nama' => 'required',
            'pop_alamat' => 'required',
            'pop_koordinat' => 'required',
            'data__site_id' => 'required',
       
        ], [
            'pop_nama.required' => 'Nama tidak boleh kosong',
            'pop_alamat.required' => 'Alamat tidak boleh kosong',
            'pop_koordinat.required' => 'Koordinat tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
        ]);

        $store_pop['pop_nama'] = strtoupper($request->pop_nama);
        $store_pop['pop_alamat'] = strtoupper($request->pop_alamat);
        $store_pop['pop_koordinat'] = $request->pop_koordinat;
        $store_pop['pop_status'] = $request->pop_status;
        $photo = $request->file('pop_file_topologi');
        if ($photo) {
            $request->validate([
                'pop_file_topologi' => 'required|max:1000|mimes:pdf',
                
            ], [
                'pop_file_topologi.required' => 'File tidak boleh kosong',
                'pop_file_topologi.max' => 'Ukuran File terlalu besar',
                'pop_file_topologi.mimes' => 'Format hanya bisa pdf',
            ]);
            $photo = $request->file('pop_file_topologi');
            $filename = Session::get('corp_id').'_'.$store_pop['pop_nama'].'.pdf';
            $path = 'topologi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store_pop['pop_file_topologi'] = $filename;
        }

        Data_pop::where('corporate_id',Session::get('corp_id'))->where('id', $id)->update($store_pop);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.pop')->with($notifikasi);
    }
    public function olt()
    {
        $data['data_olt'] = Data_Olt::query()
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->with('olt_odc')
            ->where('data__olts.corporate_id',Session::get('corp_id'))
             ->select(['data__olts.id',
             'data__olts.*',
             'data__olts.id as olt_id',
             'data_pops.pop_nama',
             'data__sites.site_nama'
             ])
            ->get();

        $data['data_pop'] = Data_pop::where('data_pops.pop_status', 'Enable')
        ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
        ->where('data_pops.corporate_id',Session::get('corp_id'))
        ->select(['data_pops.id as pop_id','data_pops.pop_nama','data__sites.site_nama'])
        ->get();
            
            
            return view('Teknisi/olt', $data);
        }
        
        
        
    public function olt_store(Request $request)
    {

        Session::flash('data_pop_id', $request->data_pop_id); #
        Session::flash('olt_nama', $request->olt_nama); #
        Session::flash('olt_pon', $request->olt_pon); #

        $request->validate([
            'data_pop_id' => 'required',
            'olt_nama' => 'required',
            'olt_pon' => 'required',
            'olt_file_topologi' => 'required|max:1000|mimes:pdf',
        ], [
            'data_pop_id.required' => 'Pop tidak boleh kosong',
            'olt_nama.required' => 'Nama Olt tidak boleh kosong',
            'olt_pon.required' => 'Jumlah Pon tidak boleh kosong',
            'olt_file_topologi.required' => 'File tidak boleh kosong',
            'olt_file_topologi.max' => 'Ukuran File terlalu besar',
            'olt_file_topologi.mimes' => 'Format hanya bisa pdf',
        ]);

        $store_olt['corporate_id'] = Session::get('corp_id');
        $store_olt['data_pop_id'] = $request->data_pop_id;
        $store_olt['olt_pon'] = $request->olt_pon;
        $store_olt['olt_nama'] = strtoupper($request->olt_nama);
        $store_olt['olt_status'] = 'Enable';
        
        $photo = $request->file('olt_file_topologi');
        $filename = Session::get('corp_id').'_'.$store_olt['olt_nama'].'.pdf';
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        
        $store_olt['olt_file_topologi'] = $filename;
        Data_Olt::create($store_olt);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan OLT',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.olt')->with($notifikasi);
    }
    
    public function update_olt(Request $request, $id)
    {
        $store_olt['data_pop_id'] = $request->data_pop_id;
        $store_olt['olt_nama'] = $request->olt_nama;
        $store_olt['olt_pon'] = $request->olt_pon;
        $store_olt['olt_status'] = $request->olt_status;

        $photo = $request->file('olt_file_topologi');
        if ($photo) {
            $filename = Session::get('corp_id').'_'.$store_olt['olt_nama'].'.pdf';
            $path = 'topologi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store_olt['olt_file_topologi'] = $filename;
        }
        
        Data_Olt::where('corporate_id',Session::get('corp_id'))->where('id', $id)->update($store_olt);

        
        $notifikasi = array(
            'pesan' => 'Berhasil update data Site',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.olt')->with($notifikasi);
    }
    
    
    public function odc()
    {        
     
        $data['data_olt'] = Data_Olt::query()
            // ->join('routers', 'routers.id', '=', 'data__olts.id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__olts.olt_status','Enable')
            ->where('data__olts.corporate_id',Session::get('corp_id'))
            ->select([
                'data__olts.id',
                'data__olts.olt_nama',
                // 'routers.router_nama',
                'data_pops.pop_nama',
                'data__sites.site_nama'
            ])
            ->get();

            
        $data['data_odc'] = Data_Odc::query()
            ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            // ->join('router_subs', 'router_subs.router_sub_id', '=', 'data__olts.id')
            // ->join('routers', 'routers.id', '=', 'router_subs.router_id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->select('data__odcs.*','data__olts.olt_nama','data_pops.pop_nama','data__sites.site_nama')
            ->orderBy('odc_id','ASC')
            ->with('odp_odc')
            ->get();

            

        return view('Teknisi/odc', $data);
    }

    public function odc_store(Request $request)
    {
            // Session::flash('odc_id', $request->odc_id); #
            Session::flash('odc_nama', $request->odc_nama); #
            Session::flash('odc_pon_olt', $request->odc_pon_olt); #
            Session::flash('odc_core', $request->odc_pon_olt); #
            Session::flash('data__olt_id', $request->data__olt_id); #
            Session::flash('odc_jumlah_port', $request->odc_jumlah_port); #
            Session::flash('odc_keterangan', $request->odc_keterangan); #
            Session::flash('odc_koordinat', $request->odc_koordinat); #
         $request->validate([
            // 'odc_id' => 'required',
            'odc_nama' => 'required',
            'odc_pon_olt' => 'required',
            'odc_core' => 'required',
            'data__olt_id' => 'required',
            'odc_jumlah_port' => 'required',
            'odc_keterangan' => 'required',
            'odc_koordinat' => 'required',
            'odc_file_topologi' => 'required|max:1000|mimes:pdf',
            'odc_lokasi_img' => 'required|max:1000|mimes:jpeg',
          

       
        ], [
            // 'odc_id.required' => 'Odc Id tidak boleh kosong',
            'odc_nama.required' => 'Nama Olt tidak boleh kosong',
            'odc_pon_olt.required' => 'Pon Olt tidak boleh kosong',
            'odc_core.required' => 'Core Kabel tidak boleh kosong',
            'data__olt_id.required' => 'Olt tidak boleh kosong',
            'odc_jumlah_port.required' => 'Jumlah Slot tidak boleh kosong',
            'odc_keterangan.required' => 'Keterangan tidak boleh kosong',
            'odc_koordinat.required' => 'Koordinat tidak boleh kosong',
            'odc_file_topologi.required' => 'File pdf tidak boleh kosong',
            'odc_file_topologi.max' => 'Ukuran File Pdf terlalu besar',
            'odc_file_topologi.mimes' => 'Topologi Format hanya bisa pdf',
            'odc_lokasi_img.required' => 'Foto lokasi tidak boleh kosong',
            'odc_lokasi_img.max' => 'Ukuran foto lokasi terlalu besar',
            'odc_lokasi_img.mimes' => 'Format foto lokasi hanya bisa jpeg',
        ]);

       

        

        $store['corporate_id'] = Session::get('corp_id');
        // $store['odc_id'] = $request->odc_id;
        $store['odc_nama'] = $request->odc_nama;
        $store['odc_pon_olt'] = $request->odc_pon_olt;
        $store['odc_core'] = $request->odc_core;
        $store['data__olt_id'] = $request->data__olt_id;
        $store['odc_jumlah_port'] = $request->odc_jumlah_port;
        $store['odc_keterangan'] = $request->odc_keterangan;
        $store['odc_koordinat'] = $request->odc_koordinat;
        $store['odc_status'] = 'Enable';

        // dd($request->all());

        $photo = $request->file('odc_file_topologi');
        $filename = Session::get('corp_id').'_'.$store['odc_nama'].'.pdf';
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store['odc_file_topologi'] = $filename;

        $photo_2 = $request->file('odc_lokasi_img');
        $filename_2 = Session::get('corp_id').'_'.$store['odc_nama'].'.jpeg';
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
        Session::flash('odc_id', $request->odc_id); #
            Session::flash('odc_nama', $request->odc_nama); #
            Session::flash('odc_pon_olt', $request->odc_pon_olt); #
            Session::flash('odc_core', $request->odc_pon_olt); #
            Session::flash('data__olt_id', $request->data__olt_id); #
            Session::flash('odc_jumlah_port', $request->odc_jumlah_port); #
            Session::flash('odc_keterangan', $request->odc_keterangan); #
            Session::flash('odc_koordinat', $request->odc_koordinat); #
         $request->validate([
            // 'odc_id' => 'required',
            'odc_nama' => 'required',
            'odc_pon_olt' => 'required',
            'odc_core' => 'required',
            'data__olt_id' => 'required',
            'odc_jumlah_port' => 'required',
            'odc_keterangan' => 'required',
            'odc_koordinat' => 'required',
        ], [
            // 'odc_id.required' => 'Odc Id tidak boleh kosong',
            'odc_nama.required' => 'Nama Olt tidak boleh kosong',
            'odc_pon_olt.required' => 'Pon Olt tidak boleh kosong',
            'odc_core.required' => 'Core Kabel tidak boleh kosong',
            'data__olt_id.required' => 'Olt tidak boleh kosong',
            'odc_jumlah_port.required' => 'Jumlah Slot tidak boleh kosong',
            'odc_keterangan.required' => 'Keterangan tidak boleh kosong',
            'odc_koordinat.required' => 'Koordinat tidak boleh kosong',
        ]);

        // $store['odc_id'] = $request->odc_id;
        $store['odc_nama'] = $request->odc_nama;
        $store['odc_pon_olt'] = $request->odc_pon_olt;
        $store['odc_core'] = $request->odc_core;
        $store['data__olt_id'] = $request->data__olt_id;
        $store['odc_jumlah_port'] = $request->odc_jumlah_port;
        $store['odc_keterangan'] = $request->odc_keterangan;
        $store['odc_koordinat'] = $request->odc_koordinat;
        $store['odc_status'] = $request->odc_status;
        // dd($store);
        $photo = $request->file('odc_file_topologi');
        if ($photo) {
            $request->validate([
            'odc_file_topologi' => 'required|max:1000|mimes:pdf',
        ], [
            'odc_file_topologi.required' => 'File pdf tidak boleh kosong',
            'odc_file_topologi.max' => 'Ukuran File Pdf terlalu besar',
            'odc_file_topologi.mimes' => 'Topologi Format hanya bisa pdf',
        ]);

             $filename = Session::get('corp_id').'_'.$store['odc_nama'].'.pdf';
            $path = 'topologi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store['odc_file_topologi'] = $filename;
        }
        $photo_2 = $request->file('odc_lokasi_img');
        if ($photo_2) {
               $request->validate([
            'odc_lokasi_img' => 'required|max:1000|mimes:jpeg',
        ], [
            'odc_lokasi_img.required' => 'Foto lokasi tidak boleh kosong',
            'odc_lokasi_img.max' => 'Ukuran foto lokasi terlalu besar',
            'odc_lokasi_img.mimes' => 'Foto lokasi Format hanya bisa jpeg',
        ]);
             $filename_2 = Session::get('corp_id').'_'.$store['odc_nama'].'.jpeg';
            $path_2 = 'topologi/' . $filename_2;
            Storage::disk('public')->put($path_2, file_get_contents($photo_2));
            $store['odc_lokasi_img'] = $filename_2;
        }

        Data_Odc::where('id', $id)->where('data__odcs.corporate_id',Session::get('corp_id'))->update($store);
        $notifikasi = array(
            'pesan' => 'Berhasil update data Odc',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odc')->with($notifikasi);
    }

    public function odp()
    {
        $data['data_odc'] = Data_Odc::query()
             ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            // ->join('router_subs', 'router_subs.router_sub_id', '=', 'data__olts.router_sub_id')
            // ->join('routers', 'routers.id', '=', 'router_subs.router_id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->select('data__odcs.id as id_odc','data__odcs.odc_nama','data__olts.olt_nama','data_pops.pop_nama','data__sites.site_nama')
            ->get();
            
            $data['data_odp'] = Data_Odp::query()
            ->join('data__odcs', 'data__odcs.id', '=', 'data__odps.data__odc_id')
             ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            // ->join('router_subs', 'router_subs.router_sub_id', '=', 'data__olts.router_sub_id')
            // ->join('routers', 'routers.id', '=', 'router_subs.router_id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->select('data__odps.*','data__odps.id as id_odp','data__odcs.odc_nama','data__olts.olt_nama','data_pops.pop_nama','data__sites.site_nama')
            ->with('data_isntalasi')
            ->orderBy('data__odps.odp_id','ASC')
            ->get();
        // echo $data['data_odp'];
        
        return view('Teknisi/odp', $data);
    }
    public function odp_instalasi($id)
    {
            $data['details_odp'] = Data_Odp::where('data__odps.id',$id)->first();
            $data['data_odp'] = Data_Odp::query()
            ->join('ftth_instalasis', 'ftth_instalasis.data__odp_id', '=', 'data__odps.id')
            ->join('input_data', 'input_data.id', '=', 'ftth_instalasis.id')
            ->select([
                'ftth_instalasis.id as idpel',
                'ftth_instalasis.reg_slot_odp',
                'ftth_instalasis.reg_in_ont',
                'data__odps.*',
                'input_data.input_nama',
                'input_data.input_alamat_pasang',
            ])
            ->orderBy('ftth_instalasis.reg_slot_odp','ASC')
            ->where('data__odps.id',$id)
            ->get();
        // echo $data['data_odp'];
        return view('Teknisi/odp_instalasi', $data);
    }
    public function odp_list($id)
    {
         $data['details_odc'] = Data_Odc::where('id',$id)->first();
        $data['data_odc'] = Data_Odc::query()
             ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->select('data__odcs.id','data__odcs.odc_nama','data__olts.olt_nama','data_pops.pop_nama','data__sites.site_nama')
            ->get();
            
             $data['data_odp'] = Data_Odp::query()
            ->join('data__odcs', 'data__odcs.id', '=', 'data__odps.data__odc_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->where('data__odps.id',$id)
            ->select('data__odps.*','data__odps.id as id_odp','data__odcs.odc_nama')
            ->with('data_isntalasi')
            ->orderBy('data__odps.odp_id','ASC')
            ->get();
        // echo $data['data_odp'];
        return view('Teknisi/odp_list', $data);
    }
    public function odp_store(Request $request)
    {
            Session::flash('odp_id', $request->odp_id); #
            Session::flash('odp_nama', $request->odp_nama); #
            Session::flash('odp_jumlah_slot', $request->odp_jumlah_slot); #
            Session::flash('odp_core', $request->odp_core); #
            Session::flash('data__odc_id', $request->data__odc_id); #
            Session::flash('odp_koordinat', $request->odp_koordinat); #
            Session::flash('odp_keterangan', $request->odp_keterangan); #
            Session::flash('odc_koordinat', $request->odc_koordinat); #
            Session::flash('odp_status', $request->odp_status); #
            Session::flash('odp_slot_odc', $request->odp_slot_odc); #
         $request->validate([
            'odp_id' => 'required',
            'odp_nama' => 'required',
            'odp_jumlah_slot' => 'required',
            'odp_core' => 'required',
            'data__odc_id' => 'required',
            'odp_koordinat' => 'required',
            'odp_keterangan' => 'required',
            'odp_status' => 'required',
            'odp_slot_odc' => 'required',
            'odp_file_topologi' => 'required|max:1000|mimes:pdf',
            'odp_lokasi_img' => 'required|max:1000|mimes:jpeg',
       
        ], [
            'odp_id.required' => 'Odp Id tidak boleh kosong',
            'odp_nama.required' => 'Nama Odp tidak boleh kosong',
            'odp_jumlah_slot.required' => 'Junmlah slot spliter tidak boleh kosong',
            'odp_core.required' => 'Core Kabel tidak boleh kosong',
            'data__odc_id.required' => 'Odc tidak boleh kosong',
            'odp_koordinat.required' => 'Koordinat tidak boleh kosong',
            'odp_keterangan.required' => 'Keterangan tidak boleh kosong',
            'odp_status.required' => 'Status tidak boleh kosong',
            'odp_slot_odc.required' => 'Slot ODC tidak boleh kosong',
            'odp_file_topologi.required' => 'File pdf tidak boleh kosong',
            'odp_file_topologi.max' => 'Ukuran File Pdf terlalu besar',
            'odp_file_topologi.mimes' => 'Topologi Format hanya bisa pdf',
            'odp_lokasi_img.required' => 'Foto lokasi tidak boleh kosong',
            'odp_lokasi_img.max' => 'Ukuran foto lokasi terlalu besar',
            'odp_lokasi_img.mimes' => 'Foto lokasi Format hanya bisa jpeg',
        ]);

           $data_odc = Data_Odc::query()
             ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            // ->join('router_subs', 'router_subs.router_sub_id', '=', 'data__olts.router_sub_id')
            // ->join('routers', 'routers.id', '=', 'router_subs.router_id')
            ->join('data_pops', 'data_pops.id', '=', 'data__olts.data_pop_id')
            ->join('data__sites', 'data__sites.id', '=', 'data_pops.data__site_id')
            ->where('data__odcs.corporate_id',Session::get('corp_id'))
            ->select('data__sites.site_nama')
            ->where('data__odcs.id',$request->data__odc_id)
            ->first();

            // dd();

        $store['corporate_id'] = Session::get('corp_id');
        $store['odp_id'] = $data_odc->site_nama.'-'.$request->odp_id;
        $store['data__odc_id'] = $request->data__odc_id;
        $store['odp_slot_odc'] = $request->odp_slot_odc;
        $store['odp_core'] = $request->odp_core;
        $store['odp_nama'] = $request->odp_nama;
        $store['odp_jumlah_slot'] = $request->odp_jumlah_slot;
        $store['odp_koordinat'] = $request->odp_koordinat;
        $store['odp_keterangan'] = $request->odp_keterangan;
        $store['odp_status'] = $request->odp_status;

        // dd($store);

        $photo = $request->file('odp_file_topologi');
        $filename = Session::get('corp_id').'_'.$store['odp_nama'].'.pdf';
        $path = 'topologi/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $store['odp_file_topologi'] = $filename;

        $photo_2 = $request->file('odp_lokasi_img');
        $filename_2 = Session::get('corp_id').'_'.$store['odp_nama'].'.jpeg';
        $path_2 = 'topologi/' . $filename_2;
        Storage::disk('public')->put($path_2, file_get_contents($photo_2));
        $store['odp_lokasi_img'] = $filename_2;

        Data_Odp::create($store);
        // dd($store);
        // dd($store);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan ODP',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odp')->with($notifikasi);
    }

    public function update_odp(Request $request,$id) {

          Session::flash('odp_id', $request->odp_id); #
            Session::flash('odp_nama', $request->odp_nama); #
            Session::flash('odp_jumlah_slot', $request->odp_jumlah_slot); #
            Session::flash('odp_core', $request->odp_core); #
            Session::flash('data__odc_id', $request->data__odc_id); #
            Session::flash('odp_koordinat', $request->odp_koordinat); #
            Session::flash('odp_keterangan', $request->odp_keterangan); #
            Session::flash('odc_koordinat', $request->odc_koordinat); #
            Session::flash('odp_status', $request->odp_status); #
            Session::flash('odp_slot_odc', $request->odp_slot_odc); #
         $request->validate([
            'odp_id' => 'required',
            'odp_nama' => 'required',
            'odp_jumlah_slot' => 'required',
            'odp_core' => 'required',
            'data__odc_id' => 'required',
            'odp_koordinat' => 'required',
            'odp_keterangan' => 'required',
            'odp_status' => 'required',
            'odp_slot_odc' => 'required',
       
        ], [
            'odp_id.required' => 'Odp Id tidak boleh kosong',
            'odp_nama.required' => 'Nama Odp tidak boleh kosong',
            'odp_jumlah_slot.required' => 'Junmlah slot spliter tidak boleh kosong',
            'odp_core.required' => 'Core Kabel tidak boleh kosong',
            'data__odc_id.required' => 'Odc tidak boleh kosong',
            'odp_koordinat.required' => 'Koordinat tidak boleh kosong',
            'odp_keterangan.required' => 'Keterangan tidak boleh kosong',
            'odp_status.required' => 'Status tidak boleh kosong',
            'odp_slot_odc.required' => 'Slot ODC tidak boleh kosong',
        ]);

        // $explode = explode('-',$request->odp_id);
        $store['odp_id'] = $request->odp_id;
        // dd($store);
        $store['data__odc_id'] = $request->data__odc_id;
        $store['odp_slot_odc'] = $request->odp_slot_odc;
        $store['odp_core'] = $request->odp_core;
        $store['odp_nama'] = $request->odp_nama;
        $store['odp_jumlah_slot'] = $request->odp_jumlah_slot;
        $store['odp_koordinat'] = $request->odp_koordinat;
        $store['odp_keterangan'] = $request->odp_keterangan;
        $store['odp_status'] = $request->odp_status;

        $photo = $request->file('odp_file_topologi');
        if ($photo) {
            $request->validate([
            'odp_file_topologi' => 'required|max:1000|mimes:pdf',
        ], [
            'odp_file_topologi.required' => 'File pdf tidak boleh kosong',
            'odp_file_topologi.max' => 'Ukuran File Pdf terlalu besar',
            'odp_file_topologi.mimes' => 'Topologi Format hanya bisa pdf',
        ]);

             $filename = Session::get('corp_id').'_'.$store['odp_nama'].'.pdf';
            $path = 'topologi/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $store['odp_file_topologi'] = $filename;
        }

        $photo_2 = $request->file('odp_lokasi_img');
        if ($photo_2) {
               $request->validate([
            'odp_lokasi_img' => 'required|max:1000|mimes:jpeg',
        ], [
            'odp_lokasi_img.required' => 'Foto lokasi tidak boleh kosong',
            'odp_lokasi_img.max' => 'Ukuran foto lokasi terlalu besar',
            'odp_lokasi_img.mimes' => 'Foto lokasi Format hanya bisa jpeg',
        ]);
             $filename_2 = Session::get('corp_id').'_'.$store['odp_nama'].'.jpeg';
            $path_2 = 'topologi/' . $filename_2;
            Storage::disk('public')->put($path_2, file_get_contents($photo_2));
            $store['odp_lokasi_img'] = $filename_2;
        }

        Data_Odp::where('id', $id)->where('corporate_id',Session::get('corp_id'))->update($store);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan ODP',
            'alert' => 'success',
        );
        return redirect()->route('admin.topo.odp')->with($notifikasi);
    }
}
