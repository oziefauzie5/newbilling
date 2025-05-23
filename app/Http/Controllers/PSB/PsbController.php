<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\NOC\NocController;
use App\Imports\Import\InputDataImport;
use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Barang\SubBarang;
use App\Models\Global\ConvertNoHp;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Transaksi\SubInvoice;

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
            // ->where('reg_progres', '>=', 2)
            ->where('reg_progres', '<=', 5)
            ->orderBy('tgl', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_hp', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_hp_2', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            });


        if ($data['router'])
            $query->where('routers.id', '=', $data['router']);
        if ($data['paket'])
            $query->where('pakets.paket_id', '=', $data['paket']);
        if ($data['data'] == "BELUM TERPASANG")
            $query->where('registrasis.reg_progres', '<', $bulan_lalu);
        if ($data['data'] == "TOTAL BULAN LALU")
            $query->whereMonth('registrasis.reg_tgl_pasang', '<', "8");
        elseif ($data['data'] == "PPP")
            $query->where('registrasis.reg_layanan', '=', "PPP");
        elseif ($data['data'] == "DHCP")
            $query->where('registrasis.reg_layanan', '=', "DHCP");
        elseif ($data['data'] == "HOTSPOT")
            $query->where('registrasis.reg_layanan', '=', "HOTSPOT");
        elseif ($data['data'] == "USER BARU")
            $query->whereMonth('reg_tgl_pasang', '=', $month);
        elseif ($data['data'] == "USER BULAN LALU")
            $query->whereMonth('reg_tgl_pasang', '=', $bulan_lalu);
        elseif ($data['data'] == "FREE")
            $query->where('reg_jenis_tagihan', '=', $data['data']);
        elseif ($data['data'] == "ISOLIR")
            $query->where('reg_status', '=', $data['data']);

        //          $variable = $query->get();
        //     foreach ($variable as $key) {

        //         // InputData::where('id',)
        //         // echo '<table><tr><td>'.$key->reg_nolayanan.'</td><td>'.$key->input_nama.'</td><td>'.$key->reg_username.'</td><td>'.$key->paket_nama.'</td></tr></table>';
        //         echo '<table><tr><td>'.$key->reg_nolayanan.'</td><td>'.$key->input_nama.'</td><td>'.date('d-m-Y',strtotime($key->reg_tgl_jatuh_tempo)).'</td><td>'.$key->reg_status.'</td></tr></table>';
        //         // echo '<table><tr><td>'.$key->input_nama.'</td><td>'.$key->reg_idpel.'</td><td>0'.$key->input_sales.'</td><td>0'.$key->reg_tgl_pasang.'</td><td>'.$key->input_alamat_pasang.'</td></tr></table>';

        //     }
        //     // $array = [12132,12006,12002,121925,111871,26019,11078,3261000,3260861,905029,10515,3261327,3261310,3260971,951063,937694,912742,884086,872146,837432,808595,785647,785168,696201,657817,529412,465101,455044,3260819,341020,308922,298400,264939,228042,202857,3260791,3260785,109606,3261060,3261057,3261041,3261004,247262,60662,33483,39556,75853,62842,17116,15804,12947,11227,18092];
        //     // InputData::whereIn('id',$array)->update(
        //     //     [
        //     //         'input_sales' => 2203910401,
        //     //     ]
        //     //     );
        //     // Registrasi::where('reg_nolayanan', 241111185720)->update(
        //     //     [
        //     //         'reg_fee' => 15000,
        //     //     ]
        //     //     );
        // dd('CILUKBA');


        $data['data_registrasi'] = $query->paginate(10);
        // dd($data['data_registrasi']);

        $data['count_inputdata'] = InputData::count();
        $data['count_registrasi'] = $query->count();
        $data['count_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_free_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '=', 'FREE')->count();
        $data['count_ps'] = Registrasi::where('reg_progres', '90')->count();
        $data['count_pb'] = Registrasi::where('reg_progres', '100')->count();
        $data['count_ppp'] = Registrasi::where('reg_layanan', 'PPP')->count();
        $data['count_total_inv'] = Invoice::where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();
        $data['count_tiket'] = Invoice::where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();

        $data['get_router'] = Router::where('router_status', 'Enable')->get();
        $data['get_paket'] = Paket::where('paket_status', 'Enable')->get();
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
            ->where('reg_progres', '>=', 90)
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
        // $data['data_user'] = User::all();
        $data['data_user'] =  User::select('users.name AS nama_user', 'users.id as user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.id', [11, 12, 13])
            ->get();
        $data['input_data'] = InputData::orderBy('input_tgl', 'DESC')->get();
        //     foreach ($data['input_data'] as $key) {
        //         echo '<table><tr><td>'.$key->input_nama.'</td><td>'.$key->input_hp.'</td><td>'.$key->password.'</td></tr></table>';

        //     }
        // dd('CILUKBA');
        $data['idpela'] = (new GlobalController)->idpel_();

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
        $id_cust = (new GlobalController)->idpel_();
        // dd($id_cust);
        // $id_cust = '12154';
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
        if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
            if (substr(trim($nomorhp2), 0, 3) == '+62') {
                $nomorhp2 = trim($nomorhp2);
            } elseif (substr($nomorhp2, 0, 1) == '0') {
                $nomorhp2 = '' . substr($nomorhp2, 1);
            }
        }
        // dd(strtoupper($request->input_nama));
        Session::flash('input_nama', ucwords($request->input_nama));
        Session::flash('input_hp', $request->input_hp);
        Session::flash('input_hp_2', $request->input_hp_2);
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
            'input_nama' => 'required',
            'input_email' => 'required',
            'input_alamat_ktp' => 'required',
            'input_alamat_pasang' => 'required',
            'input_sales' => 'required',
            'input_subseles' => 'required',
            'input_maps' => 'required',
            'input_ktp' => 'unique:input_data',
            'input_hp' => 'unique:input_data',
            'input_hp_2' => 'unique:input_data',
        ], [
            'input_nama' => 'Nama tidak boleh kosong',
            'input_email' => 'Email tidak boleh kosong',
            'input_alamat_ktp' => 'Alamat KTP tidak boleh kosong',
            'input_alamat_pasang' => 'Alamat Pasang tidak boleh kosong',
            'input_sales' => 'Sales tidak boleh kosong',
            'input_subseles' => 'Sub Sales tidak boleh kosong',
            'input_maps' => 'Maps tidak boleh kosong',
            'input_ktp.unique' => 'Nomor Identitas sudah terdaftar',
            'input_hp.unique' => 'Nomor Whatsapp 1 sudah terdaftar',
            'input_hp_2.unique' => 'Nomor Whatsapp 2 sudah terdaftar',
        ]);
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

        $cek_nohp = InputData::where('input_hp', $nomorhp)->count();

        if ($cek_nohp == 0) {
            InputData::create([
                'input_tgl' => $data['input_tgl'],
                'input_nama' => ucwords($request->input_nama),
                'id' => $id_cust,
                'input_ktp' => $request->input_ktp,
                'input_hp' => $nomorhp,
                'input_hp_2' => $nomorhp2,
                'input_email' => $request->input_email,
                'input_alamat_ktp' => strtoupper($request->input_alamat_ktp),
                'input_alamat_pasang' => strtoupper($request->input_alamat_pasang),
                'input_sales' => $request->input_sales,
                'input_subseles' => strtoupper($request->input_subseles),
                'password' => Hash::make($nomorhp),
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
        } else {
            $notifikasi = [
                'pesan' => 'Nomor Hp sudah terdaftar',
                'alert' => 'error',
            ];
            if ($request->input == 12) {
                return redirect()->route('admin.sales.index')->with($notifikasi);
            } else {
                return redirect()->route('admin.psb.list_input')->with($notifikasi);
            }
        }
    }

    public function input_data_update(Request $request)
    {
        // $request->edit_id;
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
        if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
            if (substr(trim($nomorhp2), 0, 3) == '+62') {
                $nomorhp2 = trim($nomorhp2);
            } elseif (substr($nomorhp2, 0, 1) == '0') {
                $nomorhp2 = '' . substr($nomorhp2, 1);
            }
        }
        $update['input_nama'] = $request->input_nama;
        $update['input_ktp'] = $request->input_ktp;
        $update['input_hp'] = $nomorhp;
        $update['input_hp_2'] = $nomorhp2;
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

    // DIHAPUS KARNA DI PINDAH KE REGISTRTASI CONTROLLER

    // public function edit_pelanggan($id)
    // {
    //     $data['tgl_akhir'] = date('t', strtotime(Carbon::now()));
    //     // dd($data['tgl_akhir']);
    //     $status_inet = (new NocController)->status_inet($id);
    //     // dd($status_inet['status']);
    //     $data['input_data'] = InputData::all();
    //     $data['data_router'] = Router::all();
    //     $data['data_paket'] = Paket::all();
    //     $data['data_biaya'] = SettingBiaya::first();

    //     $data['data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
    //         ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
    //         ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
    //         ->where('input_data.id', $id)
    //         ->first();
    //     // dd($data['data']);
    //     $data['data_site'] = Data_Site::get();
    //     $data['status'] = $status_inet['status'];
    //     $data['uptime'] = $status_inet['uptime'];
    //     $data['address'] = $status_inet['address'];
    //     $data['status_secret'] = $status_inet['status_secret'];
    //     return view('Registrasi/edit_registrasi', $data);
    // }
    public function input_data_import(Request $request)
    {
        dd('aa');
        Excel::import(new InputDataImport(), $request->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

    public function export_excel() {}
}
