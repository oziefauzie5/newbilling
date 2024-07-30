<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NOC\NocController;
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
    public function index(Request $request)
    {
        $month = Carbon::now()->addMonth(-0)->format('m');
        $bulan_lalu = Carbon::now()->addMonth(-1)->format('m');

        $data['router'] = $request->query('router');
        $data['paket'] = $request->query('paket');
        $data['data'] = $request->query('data');
        $data['q'] = $request->query('q');

        if ($data['router']) {
            $r = Router::where('id', $data['router'])->first();
            $data['r_nama'] = $r->router_nama;
        }
        if ($data['paket']) {
            $p = Paket::where('paket_id', $data['paket'])->first();
            $data['p_nama'] = $p->paket_nama;
        }

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('reg_progres', '<', 100)
            ->orderBy('tgl', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_mac', 'like', '%' . $data['q'] . '%');
            });


        if ($data['router'])
            $query->where('routers.id', '=', $data['router']);
        if ($data['paket'])
            $query->where('pakets.paket_id', '=', $data['paket']);
        if ($data['data'] == "PPP")
            $query->where('registrasis.reg_layanan', '=', "PPP");
        elseif ($data['data'] == "DHCP")
            $query->where('registrasis.reg_layanan', '=', "DHCP");
        elseif ($data['data'] == "HOTSPOT")
            $query->where('registrasis.reg_layanan', '=', "HOTSPOT");
        elseif ($data['data'] == "USER BARU")
            $query->whereMonth('reg_tgl_pasang', '=', $month);
        elseif ($data['data'] == "USER BULAN LALU")
            $query->whereMonth('reg_tgl_pasang', '=', $bulan_lalu);


        $data['data_registrasi'] = $query->paginate(10);
        // dd($data['data_registrasi']);

        $data['count_inputdata'] = InputData::count();
        $data['count_registrasi'] = $query->count();
        $data['count_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_free_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '=', 'FREE')->count();
        $data['count_ps'] = Registrasi::where('reg_progres', 'ps')->count();
        $data['count_pb'] = Registrasi::where('reg_progres', 'pb')->count();

        $data['get_router'] = Router::get();
        $data['get_paket'] = Paket::get();
        // $data['get_registrasi'] = Registrasi::get();

        return view('PSB/index', $data);
    }
    public function listputus_langganan(Request $request)
    {
        $month = Carbon::now()->addMonth(-0)->format('m');
        $bulan_lalu = Carbon::now()->addMonth(-1)->format('m');

        $data['router'] = $request->query('router');
        $data['paket'] = $request->query('paket');
        $data['data'] = $request->query('data');
        $data['q'] = $request->query('q');

        if ($data['router']) {
            $r = Router::where('id', $data['router'])->first();
            $data['r_nama'] = $r->router_nama;
        }
        if ($data['paket']) {
            $p = Paket::where('paket_id', $data['paket'])->first();
            $data['p_nama'] = $p->paket_nama;
        }

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('reg_progres', '=', 100)
            ->orderBy('tgl', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
            });


        if ($data['router'])
            $query->where('routers.id', '=', $data['router']);
        if ($data['paket'])
            $query->where('pakets.paket_id', '=', $data['paket']);
        if ($data['data'] == "PPP")
            $query->where('registrasis.reg_layanan', '=', "PPP");
        elseif ($data['data'] == "DHCP")
            $query->where('registrasis.reg_layanan', '=', "DHCP");
        elseif ($data['data'] == "HOTSPOT")
            $query->where('registrasis.reg_layanan', '=', "HOTSPOT");
        elseif ($data['data'] == "USER BARU")
            $query->whereMonth('reg_tgl_pasang', '=', $month);
        elseif ($data['data'] == "USER BULAN LALU")
            $query->whereMonth('reg_tgl_pasang', '=', $bulan_lalu);


        $data['data_registrasi'] = $query->paginate(10);

        $data['count_registrasi'] = $query->count();

        $data['get_router'] = Router::get();
        $data['get_paket'] = Paket::get();
        // $data['get_registrasi'] = Registrasi::get();

        return view('PSB/putus_langganan', $data);
    }
    public function list_input()
    {
        $data['data_user'] = User::all();
        $data['input_data'] = InputData::orderBy('input_tgl', 'DESC')->get();
        return view('PSB/list_input_data', $data);
    }
    public function edit_inputdata($id)
    {
        $data['input_data'] = InputData::whereId($id)->get();
        return response()->json($data['input_data']);
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
        Session::flash('input_ktp', $request->input_ktp);
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
            'input_sales' => $request->input_sales,
            'input_subseles' => ucwords($request->input_subseles),
            'password' => Hash::make($request->input_hp),
            'input_maps' => $request->input_maps,
            'input_status' => 'INPUT DATA',
            'input_keterangan' => $request->input_keterangan,
        ]);
        $notifikasi = [
            'pesan' => 'Berhasil menambahkan Pelanggan',
            'alert' => 'success',
        ];
        if ($request->input == 12) {
            return redirect()->route('admin.sales.index')->with($notifikasi);
        } else {
            return redirect()->route('admin.psb.list_input')->with($notifikasi);
        }
    }

    public function input_data_update(Request $request)
    {
        // $request->edit_id;
        // dd($request->edit_id);
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        $update['input_nama'] = $request->input_nama;
        $update['input_ktp'] = $request->input_ktp;
        $update['input_hp'] = $nomorhp;
        $update['input_email'] = $request->input_email;
        $update['input_alamat_ktp'] = $request->input_alamat_ktp;
        $update['input_alamat_pasang'] = $request->input_alamat_pasang;
        $update['input_subseles'] = $request->input_subseles;
        $update['password'] = Hash::make($nomorhp);
        $update['input_maps'] = $request->input_maps;
        $update['input_status'] = '0';
        $update['input_keterangan'] = $request->input_keterangan;
        $update['input_status'] = $request->input_status;
        InputData::where('id', $request->edit_id)->update($update);

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

        $status_inet = (new NocController)->status_inet($id);
        // dd($status_inet['status']);
        $data['input_data'] = InputData::all();
        $data['data_router'] = Router::all();
        $data['data_paket'] = Paket::all();
        $data['data_biaya'] = SettingBiaya::first();

        $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('input_data.id', $id)
            ->first();
        $data['status'] = $status_inet['status'];
        $data['uptime'] = $status_inet['uptime'];
        $data['address'] = $status_inet['address'];
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
