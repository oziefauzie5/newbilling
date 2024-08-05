<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use App\Models\Hotspot\Titikvhc;
use App\Models\Router\Router;
use Illuminate\Http\Request;

class TitikvhcController extends Controller
{
    public function titik_vhc()
    {
        // dd('test');
        $data['data_titik'] = Titikvhc::join('users', 'users.id', '=', 'titikvhcs.titik_pen_jawab_id')->paginate(10);

        return view('hotspot/titik_vhc', $data);
    }

    public function regist_titik()
    {
        $data['data_router'] = Router::get();
        return view('hotspot/regist_titik', $data);
    }

    public function store_titik(Request $request)
    {
        $data['titik_id'] = $request->titik_id; #
        $data['titik_nama'] = $request->titik_nama; #
        $data['titik_nama_titik'] = $request->titik_nama_titik; #
        $data['titik_pen_jawab_id'] = $request->titik_pen_jawab_id;
        $data['titik_pen_jawab_hp'] = $request->titik_pen_jawab_hp; #
        $data['titik_alamat'] = $request->titik_alamat; #
        $data['titik_maps'] = $request->titik_maps; #
        $data['titik_router'] = $request->titik_router; #
        $data['titik_ip'] = $request->titik_ip; #
        $data['titik_penangguang_jawab'] = $request->titik_penangguang_jawab;
        $data['titik_username'] = $request->titik_username; #
        $data['titik_password'] = $request->titik_password; #
        $data['titik_wilayah'] = $request->titik_wilayah;
        $data['titik_mac'] = $request->titik_mac; #
        $data['titik_sn'] = $request->titik_sn; #
        $data['titik_mrek'] = $request->titik_mrek; #
        $data['titik_tgl_pasang'] = $request->titik_tgl_pasang;
        $data['titik_teknisi_team'] = $request->titik_teknisi_team;
        $data['titik_kode_pactcore'] = $request->titik_kode_pactcore;
        $data['titik_kode_adaptor'] = $request->titik_kode_adaptor;
        $data['titik_kode_dropcore'] = $request->titik_kode_dropcore;
        $data['titik_kode_ont'] = $request->titik_kode_ont;
        $data['titik_fat'] = $request->titik_fat;
        $data['titik_fat_opm'] = $request->titik_fat_opm;
        $data['titik_home_opm'] = $request->titik_home_opm;
        $data['titik_los_opm'] = $request->titik_los_opm;
        $data['titik_before'] = $request->titik_before;
        $data['titik_after'] = $request->titik_after;
        $data['titik_penggunaan_dropcore'] = $request->titik_penggunaan_dropcore;
        $data['titik_catatan'] = $request->titik_catatan;
        $data['titik_progres'] = $request->titik_progres;
        $data['titik_stt_perangkat'] = $request->titik_stt_perangkat; #
        $data['titik_slotonu'] = $request->titik_slotonu;
        $data['titik_img'] = $request->titik_img;

        dd($data);
    }
}
