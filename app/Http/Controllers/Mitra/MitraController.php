<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Kelurahan;
use App\Models\Aplikasi\Data_Site;
// use App\Models\Applikasi\SettingAkun;
use App\Models\Global\ConvertNoHp;
use App\Models\Mitra\Data_Submitra;
use App\Models\Mitra\Mitra_Sub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\User;
use App\Models\Model_Has_Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\Transaksi\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Storage;


class MitraController extends Controller
{
    function index()
    {
        $data = array(
            'tittle' => 'MITRA',
            'datauser' => DB::table('users')
                ->select('users.name AS nama', 'users.alamat_lengkap', 'users.id', 'users.hp', 'users.username', 'roles.name', 'mitra_settings.mts_limit_minus', 'mitra_settings.mts_komisi')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
                ->get(),
        );
        return view('mitra/index', $data);
    }

    #LIST PIC
    function pic1_view()
    {
        $data['pic1_view'] = MitraSetting::join('model_has_roles','model_has_roles.model_id','=','mitra_settings.mts_user_id')
                                ->where('model_has_roles.role_id', 15)->orWhere('model_has_roles.role_id', 12)
                                ->where('corporate_id',Session::get('corp_id'))
                                ->with('user_mitra','mitra_site','mitra_sub')->get();
        return view('mitra/pic1_view', $data);
    }

    public function pic1_add_view()
    {
        $data['data_site'] = Data_Site::where('site_status','Enable')->where('corporate_id',Session::get('corp_id'))->get();
        return view('mitra/pic1_add_view', $data);
    }

    function store_pic1(Request $request)
    {
        Session::flash('name', $request->name); #
        Session::flash('ktp', $request->ktp); #
        Session::flash('email', $request->email); #
        Session::flash('hp', $request->hp); #
        Session::flash('alamat_lengkap', $request->alamat_lengkap); #
        Session::flash('username', $request->username); #
        Session::flash('password', $request->password); #
        Session::flash('tgl_gabung', $request->tgl_gabung); #
        Session::flash('mts_fee', $request->mts_fee); #
        Session::flash('data__site_id', $request->data__site_id); #
        Session::flash('level', $request->level); #
        // dd($request->all());
         $request->validate([
            'name' => 'required',
            'ktp' => 'required|unique:users',
            'email' => 'required',
            'hp' => 'required|unique:users',
            'alamat_lengkap' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'tgl_gabung' => 'required',
            'mts_fee' => 'required:mitra_settings',
            'data__site_id' => 'required',
            'level' => 'required',
       
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'ktp.required' => 'Nomor KTP tidak boleh kosong',
            'ktp.unique' => 'Nomor KTP sudah terdaftar',
            'email.required' => 'Email tidak boleh kosong',
            'hp.required' => 'No Hp tidak boleh kosong',
            'hp.unique' => 'Nomor Hp sudah terdaftar',
            'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'tgl_gabung.required' => 'Tanggal gabung tidak boleh kosong',
            'mts_fee.required' => 'Komisi tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'level.required' => 'Level tidak boleh kosong',
        ]);

        
            $get =  explode("|", $request->level);
            $level_id = $get[0];
            $level = $get[1];
            
            $data_level['id'] = $level_id;
            $data_level['name'] = $level;
            $data_level['guard_name'] = 'web';
        
        $cek_role = Role::whereId($level_id)->count();
        if ($cek_role == 0) {
            Role::create($data_level);
        }
        $cek_permision = Permission::whereId($level_id)->count();
        if ($cek_permision == 0) {
            Permission::create($data_level);
        }

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);

        $tgl_gabung = date('ym', strtotime($request->tgl_gabung));
        $countuser = User::where('corporate_id',Session::get('corp_id'))->count();
        $id_mitra = $tgl_gabung . $countuser . $level_id.$request->data__site_id; #FORMAT = th-bulan-urutan karyawan,level,site
        $data['id'] = $id_mitra;
        $data['corporate_id'] = Session::get('corp_id');
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        $data['password'] = Hash::make($request->password);
        $data['photo'] = 'user.png';
        $data['data__site_id'] = $request->data__site_id;
        $data['tgl_gabung'] = date('Y-m-d', strtotime($request->tgl_gabung));
        $data['status_user'] = 'Enable';
        
        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id_mitra;
        
        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;
        
        $mitra_setting['corporate_id'] = Session::get('corp_id');
        $mitra_setting['data__site_id'] = $request->data__site_id;
        $mitra_setting['mts_user_id'] = $id_mitra;
        $mitra_setting['mts_limit_minus'] = 0;
        $mitra_setting['mts_komisi'] = 0;
        $mitra_setting['mts_fee'] = $request->mts_fee;

        
        
        
        if (!RoleHasPermission::where('permission_id', $level_id)
        ->where('role_id', $level_id)
    ->get()
    ->isEmpty()) {
        User::create($data);
        Model_Has_Role::create($datarole);
    } else {
        User::create($data);
        Model_Has_Role::create($datarole);
        RoleHasPermission::create($datarolepermission);
    }
    
    MitraSetting::create($mitra_setting);
        $notifikasi = array(
            'pesan' => 'PIC berhasil ditambahkan',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.pic1_view')->with($notifikasi);
    }

      function pic1_edit_view($id)
    {
        $data['data_pic1'] = User::where('corporate_id',Session::get('corp_id'))->where('id', $id)->with('user_site','user_mitra')->first();
        $data['data_site'] = Data_Site::where('site_status','Enable')->where('corporate_id',Session::get('corp_id'))->get();
        return view('mitra/pic1_edit', $data);
    }
      function store_edit_pic1(Request $request, $id)
    {
        // dd($id);
         $validator = FacadesValidator::make(
            $request->all(),
        [
            'name' => 'required',
            'ktp' => 'required',
            'hp' => 'required',
            'email' => 'required',
            'alamat_lengkap' => 'required',
            'data__site_id' => 'required',
            'mts_fee' => 'required:mitra_settings',
            'data__site_id' => 'required:mitra_settings',
            'file' => 'max:1000|mimes:jpeg',

        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'ktp.required' => 'Nomor Ktp tidak boleh kosong.',
            'hp.required' => 'Nomor Hp tidak boleh kosong.',
            'hp.unique' => 'Nomor Hp sudah terdaftar.',
            'email.required' => 'Email tidak boleh kosong',
            'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'mts_fee.required' => 'Komisi tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'file.max' => 'Ukuran foto terlalu besar',
            'file.mimes' => 'Format hanya bisa jpeg',
        ]
    );
 if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);


        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $photo = $request->file('file');
        if ($photo) {

            $filename = Session::get('corp_id').$id.'.jpeg';
            $path = 'image/' . $filename;
            Storage::disk(     )->put($path, file_get_contents($photo));
            $data['photo'] = $filename;
        }

        $data['email'] = $request->email;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = ucwords($request->alamat_lengkap);
        $data['name'] = ucwords($request->name);
        $data['username'] = $request->username;
        $data['data__site_id'] = $request->data__site_id;
        $data['status_user'] = $request->status_user;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        User::where('corporate_id',Session::get('corp_id'))->where('id', $id)->update($data);

        $mitra_setting['mts_fee'] = $request->mts_fee;
        $mitra_setting['data__site_id'] = $request->data__site_id;
        MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id', $id)->update($mitra_setting);

        $notifikasi = [
            'pesan' => 'Data' . $request->name . ' Berhasil dirubah',
            'alert' => 'success',
        ];
        return redirect()->route('admin.mitra.pic1_view')->with($notifikasi);
    }
   

    
    function pic_addsub_view($id,$nama)
    {
        $data['id'] = $id;
        $data['nama'] = $nama;
        return view('mitra/pic_addsub_view', $data);
    }
    public function store_pic_sub(Request $request)
    {
        $mitra = MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id',$request->mts_sub_mitra_id)->first();
                  
        Session::flash('name', $request->name); #
        Session::flash('ktp', $request->ktp); #
        Session::flash('email', $request->email); #
        Session::flash('hp', $request->hp); #
        Session::flash('alamat_lengkap', $request->alamat_lengkap); #
        Session::flash('username', $request->username); #
        Session::flash('password', $request->password); #
        Session::flash('tgl_gabung', $request->tgl_gabung); #
        Session::flash('mts_fee', $request->mts_fee); #
         $request->validate([
            'name' => 'required',
            'ktp' => 'required|unique:users',
            'email' => 'required',
            'hp' => 'required|unique:users',
            'alamat_lengkap' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'tgl_gabung' => 'required',
            'mts_fee' => 'required:mitra_settings',
       
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'ktp.required' => 'Nomor KTP tidak boleh kosong',
            'ktp.unique' => 'Nomor KTP sudah terdaftar',
            'email.required' => 'Email tidak boleh kosong',
            'hp.required' => 'No Hp tidak boleh kosong',
            'hp.unique' => 'Nomor Hp sudah terdaftar',
            'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'tgl_gabung.required' => 'Tanggal gabung tidak boleh kosong',
            'mts_fee.required' => 'Fee Continue tidak boleh kosong',
        ]);

            $level_id = '16';
            $level ='SUB-PIC';
            
            $data_level['id'] = $level_id;
            $data_level['name'] = $level;
            $data_level['guard_name'] = 'web';
        
        $cek_role = Role::whereId($level_id)->count();
        if ($cek_role == 0) {
            Role::create($data_level);
        }
        $cek_permision = Permission::whereId($level_id)->count();
        if ($cek_permision == 0) {
            Permission::create($data_level);
        }

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);

        $tgl_gabung = date('ym', strtotime($request->tgl_gabung));
        $countuser = User::where('corporate_id',Session::get('corp_id'))->count();
        $countmitra = MitraSetting::where('corporate_id',Session::get('corp_id'))->count();
        $id_mitra = $tgl_gabung . $countuser .$countmitra. $level_id.$request->data__site_id; #FORMAT = th-bulan-urutan karyawan,urutan mitra,level,site
        $data['id'] = $id_mitra;
        $data['corporate_id'] = Session::get('corp_id');
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        $data['password'] = Hash::make($request->password);
        $data['photo'] = 'user.png';
        $data['data__site_id'] = $mitra->data__site_id;
        $data['status_user'] = 'Enable';
        
        
        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id_mitra;
        
        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;
        

        if (!RoleHasPermission::where('permission_id', $level_id)
        ->where('role_id', $level_id)
        ->get()
        ->isEmpty()) {
            User::create($data);
            Model_Has_Role::create($datarole);
        } else {
            User::create($data);
            Model_Has_Role::create($datarole);
            RoleHasPermission::create($datarolepermission);
        }

        $mitra_setting['corporate_id'] = Session::get('corp_id');
        $mitra_setting['data__site_id'] = $mitra->data__site_id;
        $mitra_setting['mts_user_id'] = $id_mitra;
        $mitra_setting['mts_limit_minus'] = 0;
        $mitra_setting['mts_komisi'] = 0;
        $mitra_setting['mts_fee'] = $request->mts_fee;
    MitraSetting::create($mitra_setting);

        $submitra['mts_sub_mitra_id'] = $request->mts_sub_mitra_id;
        $submitra['mts_sub_user_id'] = $id_mitra;
        $submitra['corporate_id'] = Session::get('corp_id');
        $submitra['data__site_id'] = $mitra->data__site_id;

        Mitra_Sub::create($submitra);

    
        $notifikasi = array(
            'pesan' => 'Sub PIC berhasil ditambahkan',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.pic_sub_view',['id'=>$request->mts_sub_mitra_id])->with($notifikasi);
    }

       public function pic_sub_view($id)
    {
        $data['pic_sub_view'] = Mitra_Sub::where('corporate_id',Session::get('corp_id'))->where('mts_sub_mitra_id',$id)->with(['user_submitra','submitra_site','submitra_mitra'])->get();

        $data['pic_mitra'] = MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id',$id)->with(['user_mitra'])->first();
        $data['data_site'] = Data_Site::where('site_status','Enable')->where('corporate_id',Session::get('corp_id',$id))->get();
        
        return view('mitra/pic_sub_view', $data);
    }
       public function mitra_mutasi($id)
    {
          $mutasi = DB::table('mutasi_sales')
            ->where('mutasi_sales_mitra_id' , '25055121')
            ->orderBy('created_at') // To ensure correct chronological order
            ->select(
                'mutasi_sales_mitra_id',
                'mutasi_sales_idpel',
                'mutasi_sales_admin',
                'mutasi_sales_type',
                'mutasi_sales_jumlah',
                'mutasi_sales_deskripsi',
                'created_at',
                DB::raw('SUM(CASE WHEN mutasi_sales_type = "debit" THEN mutasi_sales_jumlah ELSE 0 END) OVER (ORDER BY created_at) AS debit_balance'),
                DB::raw('SUM(CASE WHEN mutasi_sales_type = "credit" THEN mutasi_sales_jumlah ELSE 0 END) OVER (ORDER BY created_at) AS credit_balance')
            )
            ->get();
            
        // Add a final saldo column:
        $mutasi->map(function ($transaction) {
            $transaction->saldo = $transaction->debit_balance - $transaction->credit_balance;
            // dd($transaction);
            return $transaction;
        });

        return $mutasi;


        return view('mitra/pic_mutasi', $data);
    }
    
    function pic_sub_edit_view($id,$mit)
    {
        $data['data_sub_pic'] = User::where('corporate_id',Session::get('corp_id'))->where('id', $id)->with('user_site','user_mitra')->first();
        $data['pic_mitra'] = MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id',$mit)->with(['user_mitra'])->first();
        return view('mitra/pic_sub_edit', $data);
    }
  
    function store_edit_pic_sub(Request $request, $id)
    {
        
         $validator = FacadesValidator::make(
            $request->all(),
        [
            'name' => 'required',
            'ktp' => 'required',
            'hp' => 'required',
            'email' => 'required',
            'alamat_lengkap' => 'required',
            'data__site_id' => 'required',
            'mts_fee' => 'required:mitra_settings',
            'file' => 'max:1000|mimes:jpeg',

        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'ktp.required' => 'Nomor Ktp tidak boleh kosong.',
            'hp.required' => 'Nomor Hp tidak boleh kosong.',
            'hp.unique' => 'Nomor Hp sudah terdaftar.',
            'email.required' => 'Email tidak boleh kosong',
            'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
            'mts_fee.required' => 'Komisi tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'file.max' => 'Ukuran foto terlalu besar',
            'file.mimes' => 'Format hanya bisa jpeg',
        ]
    );
 if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $photo = $request->file('file');
        if ($photo) {

            $filename = Session::get('corp_id').$id.'.jpeg';
            $path = 'image/' . $filename;
            Storage::disk(     )->put($path, file_get_contents($photo));
            $data['photo'] = $filename;
        }

        $data['email'] = $request->email;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = ucwords($request->alamat_lengkap);
        $data['name'] = ucwords($request->name);
        $data['username'] = $request->username;
        $data['data__site_id'] = $request->data__site_id;
        $data['status_user'] = $request->status_user;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        User::where('corporate_id',Session::get('corp_id'))->where('id', $id)->update($data);

        $mitra_setting['mts_fee'] = $request->mts_fee;
        MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id', $id)->update($mitra_setting);

        $notifikasi = [
            'pesan' => 'Data' . $request->name . ' Berhasil dirubah',
            'alert' => 'success',
        ];
        return redirect()->route('admin.mitra.pic_sub_edit_view',['id'=>$request->mts_sub_user_id,'mit'=>$request->mts_sub_mitra_id])->with($notifikasi);
    }


    public function create()
    {
        $data = array(
            'tittle' => 'TAMBAH MITRA',

        );
        return view('mitra/create', $data);
    }

    public function addmitra(Request $request)
    {
        $validator = FacadesValidator::make(
            $request->all(),
            [

                'username' => 'unique:users',
                'hp' => 'required|unique:users',
            ],
            [
                'username.unique' => 'Username sudah digunakan.',
                'hp.unique' => 'Nomor Hp sudah terdaftar.',
            ]
        );
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $level = $get[1];


        $data_level['id'] = $level_id;
        $data_level['name'] = $level;
        $data_level['guard_name'] = 'web';
        
        $cek_role = Role::whereId($level_id)->count();
        if ($cek_role == 0) {
            Role::create($data_level);
        }
        $cek_permision = Permission::whereId($level_id)->count();
        if ($cek_permision == 0) {
            Permission::create($data_level);
        }

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);

        $tgl_gabung = date('ym', strtotime($request->tgl_gabung));
        $countuser = User::count();
        $id_mitra = $tgl_gabung . $countuser . $level_id.$request->data__site_id; #FORMAT = th-bulan-urutan karyawan,level,site
        $data['id'] = $id_mitra;
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        $data['password'] = Hash::make($request->password);
        $data['photo'] = 'user.png';
        $data['status_user'] = 'Enable';

        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id_mitra;

        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;

        $mitra_setting['mts_user_id'] = $id_mitra;
        $mitra_setting['mts_limit_minus'] = $request->limit_minus;
        $mitra_setting['mts_komisi'] = $request->mts_komisi;
        $mitra_setting['mts_komisi_sales'] = $request->mts_komisi_sales;

        MitraSetting::create($mitra_setting);

        if (!RoleHasPermission::where('permission_id', $level_id)
            ->where('role_id', $level_id)
            ->get()
            ->isEmpty()) {
            User::create($data);
            Model_Has_Role::create($datarole);
        } else {
            User::create($data);
            Model_Has_Role::create($datarole);
            RoleHasPermission::create($datarolepermission);
        }


        $notifikasi = array(
            'pesan' => 'User berhasil ditambahkan',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.index')->with($notifikasi);
    }
    public function store_edit(Request $request, $id)
    {

        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $level = $get[1];

        $data_level['id'] = $level_id;
        $data_level['name'] = $level;
        $data_level['guard_name'] = 'web';

        Role::updateorcreate($data_level);
        Permission::updateorcreate($data_level);

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $data['email'] = $request->email;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = strtoupper($request->alamat_lengkap);
        $data['name'] = strtoupper($request->name);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        if (User::whereId($id)->first('username')->username != $request->username) {
            $data['username'] = $request->username;
        }



        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $id;

        $datarolepermission['permission_id'] = $level_id;
        $datarolepermission['role_id'] = $level_id;

        $mitra_setting['mts_limit_minus'] = $request->limit_minus;
        $mitra_setting['mts_komisi'] = $request->mts_komisi;
        $mitra_setting['mts_komisi_sales'] = $request->mts_komisi_sales;

        MitraSetting::where('mts_user_id', $id)->update($mitra_setting);

        #DISABLE SEMENTAR UNTUK TES WA
        if (!RoleHasPermission::where('permission_id', $level_id)
            ->where('role_id', $level_id)
            ->get()
            ->isEmpty()) {
            User::whereId($id)->update($data);
            Model_Has_Role::where('model_id', $id)->update($datarole);
            // dd(1);
        } else {
            User::whereId($id)->update($data);
            Model_Has_Role::where('model_id', $id)->update($datarole);
            RoleHasPermission::updateorcreate($datarolepermission);
        }


        $notifikasi = array(
            'pesan' => 'Update mitra berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.edit',['id'=>$id])->with($notifikasi);
    }
    public function edit($id)
    {
        $data['data_mitra'] = DB::table('users')
            ->select('users.name AS nama', 'users.*', 'users.created_at as tgl', 'roles.name as role_name', 'roles.id as role_id', 'mitra_settings.*')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->first();

        return view('mitra/edit', $data);
    }
    public function data($id)
    {

        $saldo = (new GlobalController)->total_mutasi($id);
        $saldo_sales = (new GlobalController)->total_mutasi_sales($id);


        $data = array(
            'tittle' => 'MITRA',
            'datauser' => DB::table('users')
                ->select('users.name AS nama', 'users.alamat_lengkap', 'users.id', 'users.hp', 'users.username', 'roles.name', 'mitra_settings.mts_limit_minus', 'mitra_settings.mts_komisi')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'users.id')
                ->where('users.id', '=', $id)
                ->first(),
            'mutasi' =>  DB::table('mutasis')
                ->select('mutasis.*', 'mutasis.created_at as tgl_trx', 'mitra_settings.*')
                ->orderBy('mutasis.id', 'DESC')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
                ->where('mutasis.mt_mts_id', '=', $id)
                ->get(),
            'mutasi_saldo' =>  DB::table('mutasi_sales')
                ->select('mutasi_sales.*', 'mutasi_sales.created_at as tgl_trx', 'mitra_settings.*')
                ->orderBy('mutasi_sales.id', 'ASC')
                ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasi_sales.smt_user_id')
                ->where('mutasi_sales.smt_user_id', '=', $id)
                ->get(),
            'akun' => (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'LAPORAN')->get(),
            'saldo' => $saldo,
            'saldo_sales' => $saldo_sales,
        );
        return view('mitra/data', $data);
    }

    public function topup(Request $request, $id)
    {
        // dd($photo = $request->file('file'));
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        // $akun = (new SettingAkun())->SettingAkun()->where('akun_id', $request->cabar)->first();
        $akun = (new GlobalController)->setting_akun()->where('akun_id', $request->cabar)->first();
        // dd($akun);
        $invoice = (new GlobalController)->no_invoice_mitra();
        $count = Mutasi::count();
        if ($count == 0) {
            $count_invoice = 1;
        } else {
            $count_invoice = $count + 1;
        }
        // dd($count_invoice);
        $invoice = sprintf("%08d", $count_invoice);
        #CEK SALDO MUTASI BILLER
        $saldo = (new GlobalController)->total_mutasi($id); #SALDO MUTASI = DEBET - KREDIT
        $total = $saldo + $request->nominal;

        #mennampilkan data user sesuai hak akses
        $user = (new GlobalController)->data_user($id);

        #Admin yang sedang aktif (Membuat topup)
        $admin_user = Auth::user()->id;

        $data['mt_mts_id'] = $id;
        $data['mt_admin'] = $admin_user;
        $data['mt_kategori'] = 'TOPUP';
        $data['mt_deskripsi'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
        $data['mt_kredit'] = $request->nominal;
        $data['mt_saldo'] = $total;
        $data['mt_cabar'] = $request->cabar;



        Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI

        $data_lap['lap_id'] = 0;
        $data_lap['lap_tgl'] = $tgl_bayar;
        $data_lap['lap_inv'] = $invoice;
        $data_lap['lap_admin'] = $admin_user;
        $data_lap['lap_cabar'] = $akun->akun_nama;
        $data_lap['lap_debet'] = 0;
        $data_lap['lap_kredit'] = $request->nominal;
        $data_lap['lap_adm'] = 0;
        $data_lap['lap_jumlah_bayar'] = $request->nominal;
        $data_lap['lap_keterangan'] = 'TOPUP ' . $user->nama_user . ' INVOICE-' . $invoice;
        $data_lap['lap_akun'] = $request->cabar;
        $data_lap['lap_idpel'] = 0;
        $data_lap['lap_jenis_inv'] = "TOPUP";
        $data_lap['lap_status'] = 0;
        $photo = $request->file('file');
        $filename = $user->nama_user . date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
        $path = 'bukti-transfer/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($photo));
        $data['lap_img'] = $filename;
        Laporan::create($data_lap);



        $notifikasi = array(
            'pesan' => 'TOPUP Saldo berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }
    public function debet_saldo(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $akun = (new GlobalController)->SettingAkun()->where('akun_id', $request->cabar)->first();

        $invoice = (new GlobalController)->no_invoice_mitra();
        $saldo = (new GlobalController)->total_mutasi($id);
        $user = (new GlobalController)->data_user($id);
        $total = $saldo - $request->nominal_debet;
        // $saldo_laporan_harian = (new GlobalController)->laporan_harian($admin_user);
        // $total_laporan_harian = $saldo_laporan_harian - $request->nominal_debet;

        // dd($total_laporan_harian);

        if ($total <= 0) {
            $notifikasi = array(
                'pesan' => 'Saldo tidak mencukupi',
                'alert' => 'error',
            );
        } else {
            $invoice = (new GlobalController)->no_invoice_mitra();

            $data['mt_mts_id'] = $id;
            $data['mt_admin'] = $admin_user;
            $data['mt_kategori'] = $request->kategori;
            $data['mt_deskripsi'] = 'INVOICE-' . $invoice;
            $data['mt_kredit'] = '0';
            $data['mt_debet'] = $request->nominal_debet;
            $data['mt_saldo'] = $total;
            $data['mt_cabar'] = $request->cabar;
            Mutasi::create($data);

            // dd($data['mt_debet']);

            $data_lap['lap_id'] = 0;
            $data_lap['lap_tgl'] = $tgl_bayar;
            $data_lap['lap_inv'] = $invoice;
            $data_lap['lap_admin'] = $admin_user;
            $data_lap['lap_cabar'] = $akun->akun_nama;
            $data_lap['lap_kredit'] = 0;
            $data_lap['lap_debet'] = $data['mt_debet'];
            $data_lap['lap_adm'] = 0;
            $data_lap['lap_jumlah_bayar'] = $data['mt_debet'];
            $data_lap['lap_keterangan'] = $request->kategori . ' ' . $user->nama_user . ' INVOICE-' . $invoice;
            $data_lap['lap_akun'] = $request->cabar;
            $data_lap['lap_idpel'] = 0;
            $data_lap['lap_jenis_inv'] = "TOPUP";
            $data_lap['lap_status'] = 0;
            $photo = $request->file('file');
            $filename = $user->nama_user . date('d-m-Y', strtotime(Carbon::now())) . $photo->getClientOriginalName();
            $path = 'bukti-transfer/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['lap_img'] = $filename;
            Laporan::create($data_lap);
            // dd($data_lap);

            $notifikasi = array(
                'pesan' => $request->kategori . ' Saldo berhasil',
                'alert' => 'success',
            );
        }
        return redirect()->route('admin.mitra.data', ['id' => $id])->with($notifikasi);
    }

    function tees($request)
    {
        if($request->pic1_view){
            $pic1_view = MitraSetting::where('mts_user_id',$request->pic1_view)->first();
            $data['mutasi_lingkungan1'] = $pic1_view->mts_fee;
        }elseif($request->pic_2){
            $pic1_view = MitraSetting::where('mts_user_id',$request->pic1_view)->first();
            $data['mutasi_lingkungan1'] = $pic1_view->mts_fee;
            $data['mutasi_lingkungan2'] = $pic1_view->mts_fee;

        }
    }
}
