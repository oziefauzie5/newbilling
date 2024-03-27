<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Imports\Import\InputDataImport;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Barang\SubBarang;
use App\Models\Global\ConvertNoHp;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class PsbController extends Controller
{
    public function index()
    {
        $data['data_registrasi'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')->get();
        return view('PSB/index', $data);
    }
    public function list_input()
    {
        $data['data_user'] = User::all();
        $data['input_data'] = InputData::get();
        return view('PSB/list_InputData', $data);
    }

    public function storeValidateKtp(Request $request, $id): JsonResponse
    {
        $request->validate([
            'input_ktp' => 'unique:input_data',
        ]);
        return response()->json(["success" => "Berhasil Diskon"]);
    }
    public function store(Request $request)
    {
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        Session::flash('input_nama', ucwords($request->input_nama));
        Session::flash('input_hp', $request->input_hp);
        Session::flash('input_ktp', $nomorhp);
        Session::flash('input_email', $request->input_email);
        Session::flash('input_alamat_ktp', ucwords($request->input_alamat_ktp));
        Session::flash('input_alamat_pasang', ucwords($request->input_alamat_pasang));
        Session::flash('input_sales', ucwords($request->input_sales));
        Session::flash('input_subseles', ucwords($request->input_subseles));
        Session::flash('input_password', Hash::make($request->input_hp));
        Session::flash('input_maps', $request->input_maps);
        Session::flash('input_keterangan', ucwords($request->input_keterangan));

        $request->validate([
            'input_ktp' => 'unique:input_data',
            'input_hp' => 'unique:input_data',
        ], [
            'input_ktp.unique' => 'Nomor Identitas sudah terdaftar',
            'input_hp.unique' => 'Nomor Whatsapp sudah terdaftar',
        ]);
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));
        InputData::create([
            'input_tgl' => $data['input_tgl'],
            'input_nama' => ucwords($request->input_nama),
            'id' => $request->id,
            'input_ktp' => $request->input_ktp,
            'input_hp' => $nomorhp,
            'input_email' => $request->input_email,
            'input_alamat_ktp' => ucwords($request->input_alamat_ktp),
            'input_alamat_pasang' => ucwords($request->input_alamat_pasang),
            'input_sales' => ucwords($request->input_sales),
            'input_subseles' => ucwords($request->input_subseles),
            'input_password' => Hash::make($request->input_hp),
            'input_maps' => $request->input_maps,
            'input_status' => '0',
            'input_keterangan' => $request->input_keterangan,
        ]);
        $notifikasi = [
            'pesan' => 'Berhasil menambahkan Pelanggan',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

    public function input_data_update(Request $request, $id)
    {
        InputData::where('id', $id)->update([
            'input_nama' => $request->input_nama,
            'input_ktp' => $request->input_ktp,
            'input_hp' => $request->input_hp,
            'input_email' => $request->input_email,
            'input_alamat_ktp' => $request->input_alamat_ktp,
            'input_alamat_pasang' => $request->input_alamat_pasang,
            'input_sales' => $request->input_sales,
            'input_subseles' => $request->input_subseles,
            'input_password' => $request->input_password,
            'input_maps' => $request->input_maps,
            'input_status' => '0',
            'input_keterangan' => $request->input_keterangan,
        ]);
        $notifikasi = [
            'pesan' => 'Berhasil Edit Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

    public function input_data_delete($id)
    {
        $data = InputData::find($id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

    public function edit_pelanggan($id)
    {
        $data['input_data'] = InputData::all();
        $data['data_router'] = Router::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();

        $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('input_data.id', $id)
            ->first();
        return view('Registrasi/edit_registrasi', $data);
    }
    public function input_data_import(Request $request)
    {
        Excel::import(new InputDataImport(), $request->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }
}
