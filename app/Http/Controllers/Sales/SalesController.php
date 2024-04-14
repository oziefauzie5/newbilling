<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // dd('ciluk ba');
        $month = Carbon::now()->format('m');
        $data['id_sales'] = Auth::user()->id;

        $data['admin_user'] = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', '=', $data['id_sales'])
            ->first();
        // dd($data['admin_user']);


        $data['q'] = $request->query('q');
        $query = DB::table('input_data')
            // ->select('pelanggans.*','registrasis.nama')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->where('registrasis.sales', '=',$admin_user )
            // ->where('input_status', '<', '2')
            // ->orderBy('registrasis.created_at', 'desc')
            ->where(function ($query) use ($data) {
                $query->where('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
            });


        $data['data_pemasangan'] = $query->get();
        $data['input_data'] = InputData::where('input_status', 0)->count();
        $data['registrasi'] = Registrasi::where('reg_progres', 0)->count();
        $data['data_user'] = User::all();
        // dd($data['data_pemasangan']);
        return view('sales/index', $data);
    }

    public function list_registrasi()
    {
        $data['input_data'] = InputData::where('input_status', 0)->count();
        $data['registrasi'] = Registrasi::where('reg_progres', 0)->count();
        $data['data_pemasangan'] = InputData::where('input_status', '0')
            ->orderBy('input_tgl', 'DESC')->get();
        return view('sales/list_registrasi', $data);
    }

    // public function data_registrasi()
    // {
    //     $month = Carbon::now()->format('m');
    //     $admin_user = Auth::user()->id;
    //     $user = Auth::user()->id;
    //     $data = array(
    //         'tittle' => 'REGISTRASI',
    //         'count_registrasi' => DB::table('registrasis')->whereRaw('extract(month from created_at) = ?', [$month])->count(),
    //         'count_terpasang' => Pelanggan::where('status', 'Aktivasi')->count(),
    //         'count_terpasang' => DB::table('pelanggans')->whereRaw('extract(month from created_at) = ?', [$month])->where('status', 'Aktivasi')->count(),
    //         'data_pemasangan' => DB::table('registrasis')
    //             ->join('pelanggans', 'pelanggans.idpel', '=', 'registrasis.id')
    //             ->where('registrasis.sales', '=', $admin_user)
    //             ->where('pelanggans.status', '=', 'Aktivasi')
    //             ->orderBy('registrasis.created_at', 'desc')
    //             ->get(),
    //     );
    //     return view('master/sales/data_pelanggan', $data);
    // }
}
