<?php

namespace App\Http\Controllers\Mitra;
use App\Models\Aplikasi\Data_Site;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Kelurahan;
use App\Models\Global\ConvertNoHp;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Mitra\MutasiSales;
use App\Models\Mitra\Mitra_Sub;
use App\Models\Mitra\MitraSetting;
use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Pesan\Pesan;
use App\Models\Model_Has_Role;
use App\Models\PSB;
use App\Models\PSB\KodePromo;

class SalesController extends Controller
{
     public function sales()
    {
        // dd('sales');
        $user_id = Auth::user()->id;
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $debet = MutasiSales::where('corporate_id',Session::get('corp_id'))->where('mutasi_sales_type', 'Debit')->where('mutasi_sales_mitra_id', $user_id)->sum('mutasi_sales_jumlah');
        $kredit = MutasiSales::where('corporate_id',Session::get('corp_id'))->where('mutasi_sales_type', 'Kredit')->where('mutasi_sales_mitra_id', $user_id)->sum('mutasi_sales_jumlah');
        $saldo = $kredit - $debet;
        $data['komisi'] = $saldo;
        $m = date('m', strtotime(new Carbon()));
        $data['pencairan'] = MutasiSales::whereMonth('created_at', $m)->where('corporate_id',Session::get('corp_id'))->where('mutasi_sales_type', 'Debit')->where('mutasi_sales_mitra_id', $user_id)->sum('mutasi_sales_jumlah');
        // dd($debet);
        $data['input_data'] = InputData::where('input_sales', $user_id)->where('input_status', 'INPUT DATA')->get();
        $data['registrasi'] = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_status', '<', '5')->get();
        // dd($data['pelanggan']);

        return view('biller/sales', $data);
    }
    public function sales_input()
    {
        $user_id = Auth::user()->id;
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $data['admin_user'] = (new GlobalController)->user_admin();
        $data['paket'] = Paket::where('paket_status','Enable')->where('paket_layanan','PPP')->get();
        // $data['site'] = Data_Site::where('site_status','Enable')->get();
        
        
        
        $data['cek_role'] = MitraSetting::join('model_has_roles','model_has_roles.model_id','=','mitra_settings.mts_user_id')
        ->where('mitra_settings.mts_user_id',$user_id)
        ->select('role_id','model_id')
        ->first();

        if($data['cek_role']->role_id == 15){
            $data['sub_mitra'] = mitra_sub::join('users','users.id','=','mitra__subs.mts_sub_user_id')
            ->where('mts_sub_mitra_id',$user_id)->get();
            // $data['option'] = 'test';
        } else {
            $data['sub_mitra'] = mitra_sub::join('users','users.id','=','mitra__subs.mts_sub_mitra_id')
            ->where('mts_sub_user_id',$user_id)->get();
            // $data['option'] = 'test';
        }
        $data['wilayah'] = MitraSetting::select('mts_wilayah','mts_user_id')->where('mts_wilayah','!=','')->get();
       



        // dd($data['option']);
        return view('biller/sales_input', $data);
    }
    public function sales_update($id)
    {
        $user_id = Auth::user()->id;
        $role = (new globalController)->data_user($user_id);
        $data['input_data']= InputData::whereId($id)->first();
        $data['role'] = $role->name;
        $data['admin_user'] = (new GlobalController)->user_admin();
        $data['paket'] = Paket::where('paket_status','Enable')->where('paket_layanan','PPP')->get();

        return view('biller/sales_input_edit', $data);
    }
    public function getwilayah($id)
    {
    $data['tampil_sub_pic'] =  MitraSetting::where('corporate_id',Session::get('corp_id'))->with('user_mitra')->where('mts_wilayah', $id)->first();
    return response()->json($data);
    }
    // public function getwilayah($id)
    // {
    //   if($request->wilayah){

    //     } else {
    //          $query = MitraSetting::select('mts_wilayah')
    //           ->where('mts_wilayah',$request->kelurahan.' RW'.$request->rw )
    //           ->orWhere('mts_wilayah',$request->kelurahan);
    //          $hasil_cek = $query->first();
    //         //  dd($request->kelurahan.' RW'.$request->rw );
    //          dd($hasil_cek);
    //     }
    // }
    public function validasi_kode_promo($id)
    {
        $data['kode_promo'] = KodePromo::where('corporate_id',Session::get('corp_id'))
                                ->where('promo_id', $id)
                                ->whereDate('promo_expired', '<=', date('Y-m-d',strtotime(Carbon::now())))
                                ->first();
        // dd($data['kode_promo']);
        return response()->json($data);
    }
      public function val_kelurahan(Request $request,$id)
    {
        $data['data_kelurahan'] = Data_Kelurahan::join('data__kecamatans','data__kecamatans.id','=','data__kelurahans.data__kecamatan_id')
                                                ->join('data__sites','data__sites.id','=','data__kecamatans.data__site_id')
                                                ->where('data__kelurahans.corporate_id', Session::get('corp_id'))
                                                ->where('data__kelurahans.kel_nama', $id)
                                                ->select(['data__kelurahans.kel_nama','data__kecamatans.kec_nama','data__sites.site_nama','data__sites.id as id_site','data__kelurahans.id as id_kel'])
                                                ->first();
        if($data['data_kelurahan']){
            $wilayah = $data['data_kelurahan']->kel_nama .' RW'.$request->rw;
            $user_mitra = MitraSetting::join('users','users.id','=','mitra_settings.mts_user_id')->Where('mitra_settings.mts_wilayah','=',$wilayah)
                                                ->orWhere('mitra_settings.mts_wilayah','=',$data['data_kelurahan']->kel_nama)
                                                // ->select(['data__kelurahans.kel_nama','data__kecamatans.kec_nama','data__sites.site_nama','data__sites.id as id_site','data__kelurahans.id as id_kel'])
                                               ->first();
            $data['user_mitra'] = $user_mitra;
        } else {
            $data['user_mitra'] = '';
        }
                                                // dd( $data['data_kelurahan']);
        return response()->json($data);
    }
    public function sales_store(Request $request)
    {
        $user = (new GlobalController)->user_admin();
        $user_nama = $user['user_nama'];
        $user_id = $user['user_id'];

        $cek_role = Model_Has_Role::join('roles','roles.id','=','model_has_roles.role_id')
                        ->where('model_has_roles.model_id',$user_id)
                        ->first();
        
        

        $id_cust = (new GlobalController)->idpel_();

        $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
        if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
            if (substr(trim($nomorhp2), 0, 3) == '+62') {
                $nomorhp2 = trim($nomorhp2);
            } elseif (substr($nomorhp2, 0, 1) == '0') {
                $nomorhp2 = '' . substr($nomorhp2, 1);
            }
        }
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        Session::flash('input_nama', strtoupper($request->input_nama));
        Session::flash('input_hp', $request->input_hp);
        Session::flash('input_hp_2', $request->input_hp_2);
        Session::flash('input_ktp', $request->input_ktp);
        Session::flash('input_email', $request->input_email);
        Session::flash('input_alamat_ktp', strtoupper($request->input_alamat_ktp));
        Session::flash('rt_ktp', strtoupper($request->rt_ktp));
        Session::flash('rw_ktp', strtoupper($request->rw_ktp));
        Session::flash('kelurahan_ktp', strtoupper($request->kelurahan_ktp));
        Session::flash('kecamatan_ktp', strtoupper($request->kecamatan_ktp));
        Session::flash('kota_ktp', strtoupper($request->kota_ktp));
        Session::flash('input_alamat', strtoupper($request->input_alamat));
        Session::flash('rt', strtoupper($request->rt));
        Session::flash('rw', strtoupper($request->rw));
        Session::flash('kelurahan', strtoupper($request->kelurahan));
        Session::flash('kecamatan', strtoupper($request->kecamatan));
        Session::flash('kota', strtoupper($request->kota));
        Session::flash('input_sales', strtoupper($request->input_sales));
        Session::flash('sub_sales', strtoupper($request->sub_sales));
        Session::flash('input_password', Hash::make($request->input_hp));
        Session::flash('input_maps', $request->input_maps);
        Session::flash('input_sub_pic', $request->input_sub_pic);
        Session::flash('input_keterangan', strtoupper($request->input_keterangan));
        Session::flash('input_promo', strtoupper($request->input_promo));
        Session::flash('wilayah', strtoupper($request->wilayah));

        

        $request->validate([
            'input_ktp' => 'unique:input_data',
            'input_hp' => 'unique:input_data',
            'input_hp_2' => 'unique:input_data',
        ], [
            'input_ktp.unique' => 'Nomor Identitas sudah terdaftar',
            'input_hp.unique' => 'Nomor Whatsapp sudah terdaftar',
            'input_hp_2.unique' => 'Nomor Whatsapp cadangan sudah terdaftar',
        ]);
      
        $data['input_tgl'] = date('Y-m-d', strtotime(carbon::now()));

        $cek_nohp = InputData::where('input_hp', $nomorhp)->count();
        $cek_nohp2 = InputData::where('input_hp', $nomorhp2)->count();

        $get_site =  explode("|", $request->kota);
        $site_id = $get_site[0];
        $site_nama = $get_site[1];
        // dd($get_site);

        if ($cek_nohp == 0) {

            $input['input_tgl'] = $data['input_tgl'];
            $input['input_nama'] = strtoupper($request->input_nama);
            $input['corporate_id']= Session::get('corp_id');
            $input['id'] = $id_cust;
            $input['input_ktp'] = $request->input_ktp;
            $input['data__site_id'] = $site_id;
            $input['input_hp'] = $nomorhp;
            $input['input_hp_2'] = $nomorhp2;
            $input['input_email'] = $request->input_email;
            $input['input_alamat_ktp'] = strtoupper($request->input_alamat_ktp).', RT'. strtoupper($request->rt_ktp).'/RW'. strtoupper($request->rw_ktp).', KEL. '. strtoupper($request->kelurahan_ktp).', KEC. '. strtoupper($request->kecamatan_ktp).', KOTA/KAB. '. strtoupper($request->kota_ktp);
            $input['input_alamat_pasang'] = strtoupper($request->input_alamat).', RT'. strtoupper($request->rt).'/RW'. strtoupper($request->rw).', KEL. '. strtoupper($request->kelurahan).', KEC. '. strtoupper($request->kecamatan).', KOTA/KAB. '. strtoupper($site_nama);
            $input['input_subseles'] = strtoupper($request->sub_sales);
            $input['password'] = Hash::make($nomorhp);
            $input['input_maps'] = $request->input_maps;
            $input['input_status'] = 'INPUT DATA';
            $input['input_keterangan'] = strtoupper($request->input_paket .' - '.$request->input_keterangan);
            $input['input_promo'] = $request->input_promo;
            // dd($input);

            if($cek_role->role_id == 16 ){
                $input['input_sales'] = $request->input_sales;
                $input['input_sub_pic'] = $user_id;
            } else {
                $input['input_sales'] = $user_id;
                if($request->input_sub_pic){
                    $input['input_sub_pic'] = $request->input_sub_pic;
                }
            }
            InputData::create($input);

            $status = (new GlobalController)->whatsapp_status();
            if ($status) {
                if ($status->wa_status == 'Enable') {
                    $status_pesan = '0';
                } else {
                    $status_pesan = '10';
                }
            }else{
                $status_pesan = '10';
            }


            $pesan_group['layanan'] = 'CS';
            $pesan_group['ket'] = 'input data';
            $pesan_group['corporate_id']= Session::get('corp_id');
            $pesan_group['target'] = env('GROUP_REGISTRASI');;
            $pesan_group['nama'] = $user_nama;
            $pesan_group['status'] = $status_pesan;
            $pesan_group['pesan'] = '               -- LIST REGISTRASI --

Nama : ' . strtoupper($request->input_nama) . '
Alamat : ' . strtoupper($request->input_alamat).', RT '. strtoupper($request->rt).', RW '. strtoupper($request->rw).', KEL. '. strtoupper($request->kelurahan).', KEC. '. strtoupper($request->kecamatan).', KOTA/KAB. '. strtoupper($site_nama).'

Paket : *' . strtoupper($request->input_paket) . '*
Catatan : *' . strtoupper($request->input_keterangan) . '*
Tanggal Registrasi : ' . date('d-m-Y', strtotime($request->tgl_regist)) . ' 

Input Data By : *' . strtoupper($user_nama) . '*
';
Pesan::create($pesan_group);



            $notifikasi = [
                'pesan' => 'Berhasil input data',
                'alert' => 'success',
            ];
            return redirect()->route('admin.sales.sales')->with($notifikasi);
        } else {
            $notifikasi = [
                'pesan' => 'Nomor Hp sudah terdaftar',
                'alert' => 'error',
            ];
            return redirect()->route('admin.sales.sales_input')->with($notifikasi);
        }
    }
    public function update_store(Request $request, $id)
    {
         $nomorhp2 = preg_replace("/[^0-9]/", "", $request->input_hp_2);
        if (!preg_match('/[^+0-9]/', trim($nomorhp2))) {
            if (substr(trim($nomorhp2), 0, 3) == '+62') {
                $nomorhp2 = trim($nomorhp2);
            } elseif (substr($nomorhp2, 0, 1) == '0') {
                $nomorhp2 = '' . substr($nomorhp2, 1);
            }
        }
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->input_hp);
        $udpate['input_nama']= $request->input_nama;
        $udpate['input_ktp']= $request->input_ktp;
        $udpate['input_hp']= $nomorhp;
        $udpate['input_hp_2']=  $nomorhp2;
        $udpate['input_email']= $request->input_email;
        $udpate['input_alamat_ktp']= $request->input_alamat_ktp;
        $udpate['input_alamat_pasang']= $request->input_alamat;
        $udpate['input_keterangan']= $request->input_keterangan;
        $udpate['input_maps']= $request->input_maps;
        $udpate['input_subseles']= $request->input_subseles;
        $udpate['input_promo']= $request->input_promo;

        $udpate['password']= Hash::make($nomorhp);
        $udpate['input_status']= $request->input_status;

        InputData::whereId($id)->where('corporate_id',Session::get('corp_id'))->update($udpate);
       $notifikasi = [
                'pesan' => 'Berhasil update data',
                'alert' => 'success',
            ];
            return redirect()->route('admin.sales.sales')->with($notifikasi);
    }
    public function mutasi_sales()
    {
        $admin_user = Auth::user()->id;
        $query =  DB::table('mutasi_sales')
            ->orderBy('mutasi_sales.created_at', 'DESC')
            ->where('mutasi_sales.mutasi_sales_mitra_id', '=', $admin_user);
        $data['mutasi_sales'] = $query->get();

        return view('biller/mutasi_sales', $data);
    }
    public function pelanggan(Request $request)
    {
        $date = Carbon::now();
        $month = date('Y-m-01', strtotime($date));
        $user = (new GlobalController)->user_admin();
        $user_id = $user['user_id'];
        $role = (new globalController)->data_user($user_id);
        $data['role'] = $role->name;
        $data['q'] = $request->query('q');
        $data['putus'] = $request->query('putus');
        $data['fee'] = $request->query('fee');

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*','ftth_fees.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('ftth_fees', 'ftth_fees.fee_idpel', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('ftth_fees.reg_mitra', '=', $user_id)
            ->where('reg_progres', '>=', 3)
            ->orderBy('tgl', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('reg_progres', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_username', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
                $query->orWhere('reg_jenis_tagihan', 'like', '%' . $data['q'] . '%');
            });
        // dd($data['putus']);
        if ($data['putus'])
            $query->where('registrasis.reg_progres', '>', 5);
        if ($data['fee'])
            $query->whereDate('reg_tgl_pasang', '<', $month);

        $query->whereIn('registrasis.reg_progres', [3, 4, 5]);
        $query->where('reg_jenis_tagihan', '!=', 'FREE');

        $data['data_pelanggan'] = $query->get();

        // dd($data['data_pelanggan']);



        // // dd($month);
        #COUNT PELANGGANG AKTIF
        $query_aktif = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '<=', 5)
            ->whereDate('reg_tgl_pasang', '<', $month);
        // $data['pelanggan_aktif'] = $query_aktif->where('reg_jenis_tagihan', '!=', 'FREE')->get();
        // // foreach ($data['pelanggan_aktif'] as $key ) {
        // //     # code...
        // //     echo '<table><tr><th>'.$key->reg_nolayanan.'</th><th>'.$key->input_nama.'</th></tr></table>';
        // // }

        // // dd('test');
        $data['pelanggan_aktif'] = $query_aktif->where('reg_jenis_tagihan', '!=', 'FREE')->count();

        // #COUNT PELANGGAN BULAN INI
        $query_bulan_ini = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '<=', 5)
            ->whereDate('reg_tgl_pasang', '>=', $month);
        $data['pelanggan_bulan_ini'] = $query_bulan_ini->where('reg_jenis_tagihan', '!=', 'FREE')->count();

        #COUNT PELANGGAN PUTUS
        $query_putus = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '>', 5);
        $data['pelanggan_putus'] = $query_putus->count();

        #COUNT PELANGGAN FREE
        $query_free = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('input_data.input_sales', '=', $user_id)
            ->where('reg_progres', '>=', 3);
        $data['total_pelanggan'] = $query_free->count();
        $data['pelanggan_free'] = $query_free->where('reg_jenis_tagihan', '=', 'FREE')->count();

        #COUNT PELANGGAN LUNAS
        $query_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', 'PAID')
            ->where('registrasis.reg_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $user_id);
        $data['pelanggan_lunas'] = $query_lunas->count();


        #COUNT PELANGGAN BELUM LUNAS
        $query_belum_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            // ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', '!=', 'PAID')
            ->where('registrasis.reg_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $user_id);

        $data['pelanggan_belum_lunas'] = $query_belum_lunas->get();
        // foreach ($data['pelanggan_belum_lunas'] as $key ) {
        //         # code...
        //         echo '<table><tr><th>'.$key->reg_nolayanan.'</th><th>'.$key->input_nama.'</th></tr></table>';
        //     }

        //     dd('test');
        $data['pelanggan_belum_lunas'] = $query_belum_lunas->count();
         $debet = MutasiSales::where('mutasi_sales_mitra_id', $user_id)->where('mutasi_sales_type', 'Debit')->sum('mutasi_sales_jumlah');
        $kredit = MutasiSales::where('mutasi_sales_mitra_id', $user_id)->where('mutasi_sales_type', 'Credit')->sum('mutasi_sales_jumlah');

        $data['komisi'] = $kredit - $debet;
        // $data['komisi'] = (new globalController)->total_mutasi_sales($user_id);

        return view('biller/data_pelanggan_sales', $data);
    }

     public function mutasi_sales_pdf(Request $request)
    {
        // dd('test');
        // $month = date('m',strtotime(Carbon::now()));
        // $year = date('Y',strtotime(Carbon::now()));
        $month = date('Y-m-01', strtotime(Carbon::now()));
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;

        $data['start_date'] =  $request->start_date;
        $data['end_date'] =  $request->end_date;

        $query =  DB::table('mutasi_sales')
            ->orderBy('mutasi_sales.mutasi_sales_tgl_transaksi', 'ASC')
            ->where('mutasi_sales.mutasi_sales_mitra_id', '=', $data['admin_user'])
            ->whereDate('mutasi_sales.created_at', '>=', $data['start_date'])
            ->whereDate('mutasi_sales.created_at', '<=', $data['end_date']);
        $data['mutasi_sales'] = $query->get();

        $query_pel =  InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->orderBy('registrasis.reg_tgl_pasang', 'ASC')
            ->where('users.id', '=', $data['admin_user']);
        $data['data_pelannggan'] = $query_pel->get();

        $querycount = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('users.id', '=', $data['admin_user']);
        $data['total_pel'] = $querycount->count();
        $data['count_pelfree'] = $querycount->where('registrasis.reg_jenis_tagihan', '=', 'Free')->count();

        $querycount2 = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_progres', '>', 5)
            ->where('users.id', '=', $data['admin_user']);
        $data['count_putus'] = $querycount2->count();

        $querycount3 = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('users.id', '=', $data['admin_user'])
            ->whereDate('reg_tgl_pasang', '>', $month)
            ->where('reg_jenis_tagihan', '!=', 'FREE');
        $data['count_pel_baru'] = $querycount3->count();

        $data['pel_aktif'] = InputData::join('registrasis', 'registrasis.reg_idpel', 'input_data.id')
            ->join('users', 'users.id', '=', 'input_data.input_sales')
            ->where('registrasis.reg_progres', '<=', 5)
            ->where('registrasis.reg_jenis_tagihan', '!=', 'FREE')
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('users.id', '=', $data['admin_user'])
            ->count();

        #COUNT PELANGGAN LUNAS
        $query_lunas = Invoice::select('input_data.*', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->whereDate('inv_tgl_bayar', '>=', $month)
            ->whereDate('reg_tgl_pasang', '<', $month)
            ->where('inv_status', 'PAID')
            ->where('registrasis.reg_jenis_tagihan', 'PRABAYAR')
            ->where('input_sales', $data['admin_user']);
        $data['pelanggan_lunas'] = $query_lunas->count();

        $debet = MutasiSales::where('mutasi_sales_mitra_id', $data['admin_user'])->where('mutasi_sales_type', 'Debit')->sum('mutasi_sales_jumlah');
        $kredit = MutasiSales::where('mutasi_sales_mitra_id', $data['admin_user'])->where('mutasi_sales_type', 'Credit')->sum('mutasi_sales_jumlah');

        $data['saldo'] = $kredit - $debet;
        return view('biller/mutasi_pdf', $data);
        $pdf = App::make('dompdf.wrapper');
        $html = view('biller/mutasi_pdf', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'potraid');
        return $pdf->download('Mutasi-Sales.pdf');
    }
}
