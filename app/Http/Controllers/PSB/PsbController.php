<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Imports\Import\InputDataImport;
use App\Models\Aplikasi\Data_Site;
use App\Models\Global\ConvertNoHp;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Transaksi\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class PsbController extends Controller
{
    public function ftth(Request $request)
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

        $query = Registrasi::query()
        ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
    ->with(['registrasi_paket']);
        // ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile');
        // ->join('ftth_fees', 'ftth_fees.id', '=', 'registrasis.reg_idpel')
        // ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
        // ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router');
        // ->where('input_data.corporate_id',Session::get('corp_id'))
        // ->select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'routers.id as router_id','routers.router_nama','pakets.paket_id','pakets.paket_nama')
        // ->where('reg_progres', '<=', 5)
        // ->orderBy('tgl', 'DESC')
            // ->where(function ($query) use ($data) {
            //     $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
            //     // $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('input_hp', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('input_hp_2', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
            //     $query->orWhere('reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            // });


        // if ($data['router'])
        //     $query->where('routers.id', '=', $data['router']);
        // if ($data['data'] == "BELUM TERPASANG")
        //     $query->where('registrasis.reg_progres', '<', $bulan_lalu);
        // if ($data['data'] == "TOTAL BULAN LALU")
        //     $query->whereMonth('registrasis.reg_tgl_pasang', '<', "8");
        // elseif ($data['data'] == "PPP")
        //     $query->where('registrasis.reg_layanan', '=', "PPP");
        // elseif ($data['data'] == "DHCP")
        //     $query->where('registrasis.reg_layanan', '=', "DHCP");
        // elseif ($data['data'] == "HOTSPOT")
        //     $query->where('registrasis.reg_layanan', '=', "HOTSPOT");
        // elseif ($data['data'] == "USER BARU")
        //     $query->whereMonth('reg_tgl_pasang', '=', $month);
        // elseif ($data['data'] == "USER BULAN LALU")
        //     $query->whereMonth('reg_tgl_pasang', '=', $bulan_lalu);
        // elseif ($data['data'] == "FREE")
        //     $query->where('reg_jenis_tagihan', '=', $data['data']);
        // elseif ($data['data'] == "ISOLIR")
        //     $query->where('reg_status', '=', $data['data']);
        
        
        
        // if ($data['paket'])
        //     $query->find('paket_id', '=', $data['paket']);
        // dd($query->get());
        
        $data['data_registrasi'] = $query->paginate(10);
        //  $data['data_registrasi'] = Registrasi::with('registrasi_router','registrasi_paket')->get();
        // echo $data['data_registrasi'];


        $data['count_inputdata'] = InputData::where('corporate_id',Session::get('corp_id'))->count();
        $data['count_registrasi'] = $query->count();
        $data['count_berlangganan'] = Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_free_berlangganan'] = Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '=', 'FREE')->count();
        $data['count_ps'] = Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_progres', '90')->count();
        $data['count_pb'] = Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_progres', '100')->count();
        $data['count_ppp'] = Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_layanan', 'PPP')->count();
        $data['count_total_inv'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();
        $data['count_tiket'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();

        $data['get_router'] = Router::where('corporate_id',Session::get('corp_id'))->where('router_status', 'Enable')->get();
        $data['get_paket'] = Paket::where('corporate_id',Session::get('corp_id'))->where('paket_status', 'Enable')->get();
        $data['get_registrasi'] = Registrasi::get();

        return view('PSB/ftth', $data);
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

        $data['get_router'] = Router::where('corporate_id',Session::get('corp_id'))->get();
        $data['get_paket'] = Paket::where('corporate_id',Session::get('corp_id'))->get();

        return view('PSB/putus_langganan', $data);
    }



    public function list_input()
    {
        $data['data_user'] =  User::select('users.name AS nama_user', 'users.id as user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('corporate_id',Session::get('corp_id'))
            ->whereIn('roles.id', [11, 12, 13])
            ->get();
        $data['input_data'] = InputData::orderBy('input_tgl', 'DESC')->where('corporate_id',Session::get('corp_id'))->get();
        $data['idpela'] = (new GlobalController)->idpel_();

        return view('PSB/list_input_data', $data);
    }
    public function input_data()
    {
        $data['data_user'] =  User::select('users.name AS nama_user', 'users.id as user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.id', [12])
            ->where('status_user','Enable')->where('corporate_id',Session::get('corp_id'))
            ->get();
             $data['input_data'] = InputData::orderBy('input_tgl', 'DESC')->where('corporate_id',Session::get('corp_id'))->get();
             $data['data_site'] = Data_Site::where('site_status','Enable')->where('corporate_id',Session::get('corp_id'))->get();
             
             return view('PSB/input_data', $data);
            }
            public function input_data_update_view($id)
            {
                $data['data'] = InputData::orderBy('input_tgl', 'DESC')->where('corporate_id',Session::get('corp_id'))->whereId($id)->with('user_sales')->first();

            return view('PSB/input_data_update', $data);
    }
    // public function edit_inputdata($id)
    // {
    //     $data['input_data'] = InputData::whereId($id)->get();
    //     return response()->json($data['input_data']);
    // }

    public function store(Request $request)
    {
        // DD($request->all());
       
        Session::flash('input_nama', strtoupper($request->input_nama));
        Session::flash('input_hp', $request->input_hp);
        Session::flash('input_hp_2', $request->input_hp_2);
        Session::flash('input_ktp', $request->input_ktp);
        Session::flash('input_email', $request->input_email);
        Session::flash('kecamatan', strtoupper($request->kecamatan));
        Session::flash('kelurahan', strtoupper($request->kelurahan));
        Session::flash('kota', strtoupper($request->kota));
        Session::flash('rw', strtoupper($request->rw));
        Session::flash('rt', strtoupper($request->rt));
        Session::flash('kecamatan1', strtoupper($request->kecamatan1));
        Session::flash('kelurahan1', strtoupper($request->kelurahan1));
        Session::flash('kota1', strtoupper($request->kota1));
        Session::flash('rw1', strtoupper($request->rw1));
        Session::flash('rt1', strtoupper($request->rt1));
        Session::flash('input_alamat_ktp', strtoupper($request->input_alamat_ktp));
        Session::flash('input_alamat_pasang', strtoupper($request->input_alamat_pasang));
        Session::flash('input_sales', strtoupper($request->input_sales));
        Session::flash('input_subseles', strtoupper($request->input_subseles));
        Session::flash('input_password', Hash::make($request->input_hp));
        Session::flash('input_maps', $request->input_maps);
        Session::flash('input_keterangan', strtoupper($request->input_keterangan));
        
        
        $request->validate([
            'input_nama' => 'required',
            'input_email' => 'required',
            'input_alamat_ktp' => 'required',
            'input_alamat_pasang' => 'required',
            'input_maps' => 'required',
            'input_ktp' => 'unique:input_data',
            'input_hp' => 'unique:input_data',
            'input_hp_2' => 'unique:input_data',
            'kecamatan'=> 'required',   
            'kelurahan'=> 'required',
            'kota'=> 'required',
            'rw'=> 'required',
            'rt'=> 'required',
            'kecamatan1'=> 'required',
            'kelurahan1'=> 'required',
            'kota1'=> 'required',
            'rw1'=> 'required',
            'rt1'=> 'required',
        ], [
            'input_nama' => 'Nama tidak boleh kosong',
            'input_email' => 'Email tidak boleh kosong',
            'input_alamat_ktp' => 'Alamat KTP tidak boleh kosong',
            'input_alamat_pasang' => 'Alamat Pasang tidak boleh kosong',
            'input_maps' => 'Maps tidak boleh kosong',
            'input_ktp.unique' => 'Nomor Identitas sudah terdaftar',
            'input_hp.unique' => 'Nomor Whatsapp 1 sudah terdaftar',
            'input_hp_2.unique' => 'Nomor Whatsapp 2 sudah terdaftar',
            'kecamatan.required'=> 'Kecamatan tidak boleh kosong',
            'kelurahan.required'=> 'Kelurahan tidak boleh kosong',
            'kota.required'=> 'Kota tidak boleh kosong',
            'rw.required'=> 'Rw tidak boleh kosong',
            'rt.required'=> 'Rt tidak boleh kosong',
            'kecamatan1.required'=> 'Kecamatan tidak boleh kosong',
            'kelurahan1.required'=> 'Kelurahan alamat pemasangan tidak boleh kosong',
            'kota1.required'=> 'kota alamat pemasangan tidak boleh kosong',
            'rw1.required'=> 'Rw alamat pemasangan tidak boleh kosong',
            'rt1.required'=> 'Rt alamat pemasangan tidak boleh kosong',
        ]);
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
                $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
                if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
                    if (substr(trim($nomorhp2), 0, 3) == '+62') {
                        $nomorhp2 = trim($nomorhp2);
                    } elseif (substr($nomorhp2, 0, 1) == '0') {
                        $nomorhp2 = '' . substr($nomorhp2, 1);
                    }
                }
        $get_site =  explode("|", $request->kota1);
        $site_id = $get_site[0];
        $site_nama = $get_site[1];

        $id_cust = (new GlobalController)->idpel_();
        // dd($id_cust);
        // $id_cust = '12154';
      
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

        $cek_nohp = InputData::where('input_hp', $nomorhp)->where('corporate_id',Session::get('corp_id'))->count();

        if ($cek_nohp == 0) {
            InputData::create([
                'corporate_id' => Session::get('corp_id'),
                'data__site_id' => $site_id,
                // 'input_tgl' => $data['input_tgl'],
                'input_nama' => strtoupper($request->input_nama),
                'id' => $id_cust,
                'input_ktp' => $request->input_ktp,
                'input_hp' => $nomorhp,
                'input_hp_2' => $nomorhp2,
                'input_email' => $request->input_email,
                'input_alamat_ktp' => strtoupper($request->input_alamat_pasang.', KEC. '.$request->kecamatan1.', DESA/KEL. '.$request->kelurahan.', KOTA/KAB. '.$request->kota.', RT '.$request->rw.', RT '.$request->rt),
                'input_alamat_pasang' => strtoupper($request->input_alamat_pasang.', KEC. '.$request->kecamatan1.', DESA/KEL. '.$request->kelurahan1.', KOTA/KAB. '.$site_nama.', RT '.$request->rw1.', RT '.$request->rt1),
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
                return redirect()->route('admin.psb.list_input')->with($notifikasi);
        } else {
            $notifikasi = [
                'pesan' => 'Nomor Hp sudah terdaftar',
                'alert' => 'error',
            ];
                return redirect()->route('admin.psb.list_input')->with($notifikasi);
        }
    }

    public function input_data_update(Request $request, $id)
    {

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
        $update['input_keterangan'] = $request->input_keterangan;
        $update['input_status'] = $request->input_status;
        InputData::where('id', $id)->where('corporate_id',Session::get('corp_id'))->update($update);

        $notifikasi = [
            'pesan' => 'Berhasil Edit Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

    public function input_data_delete($id)
    {
        $data = InputData::where('corporate_id',Session::get('corp_id'))->find($id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }


}
