<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Global\ConvertNoHp;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Model_Has_Role;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\RoleHasPermission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Models\Permission;
use App\Models\Pesan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:admin|noc']);
    }

    function index()
    {
        $data['data_user'] = User::join('model_has_roles', 'model_has_roles.model_id','=','users.id')
                                ->join('roles', 'roles.id','=','model_has_roles.role_id')
                                ->join('data__sites', 'data__sites.id','=','users.data__site_id')
                                ->where('users.corporate_id',Session::get('corp_id'))
                                ->whereIn('model_has_roles.role_id',[1,2,3,4,5,6,7,8,9])
                                ->select(['users.id as id_user',
                                        'users.name as nama_user',
                                        'users.alamat_lengkap',
                                        'users.hp',
                                        'users.photo',
                                        'users.email',
                                        'users.ktp',
                                        'users.username',
                                        'users.tgl_gabung',
                                        'users.status_user',
                                        'data__sites.site_nama',
                                        'roles.name as level',
                                        'roles.id as role_id',
                                        ])
                                ->get();            
        $data['role'] = Role::where('id','!=','13')->get();
        $data['data_site'] = Data_Site::where('corporate_id',Session::get('corp_id'))->where('site_status', 'Enable')->get();
        return view('User/index', $data);
    }

    function store(Request $request)
    {
        $validator = FacadesValidator::make(
            $request->all(),
            [
                'name' => 'required',
                'ktp' => 'required',
                'hp' => 'required|unique:users',
                'email' => 'required',
                'alamat_lengkap' => 'required',
                'data__site_id' => 'required',
                'username' => 'unique:users',
                'password' => 'required',
                'level' => 'required',
                
            ],
            [
                'name.required' => 'Nama tidak boleh kosong.',
                'ktp.required' => 'Nomor Ktp tidak boleh kosong.',
                'hp.required' => 'Nomor Hp tidak boleh kosong.',
                'hp.unique' => 'Nomor Hp sudah terdaftar.',
                'email.required' => 'Email tidak boleh kosong',
                'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
                'data__site_id.required' => 'Site tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan.',
                'password.required' => 'Password tidak boleh kosong',
                'level.required' => 'Level tidak boleh kosong',
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
        $tgl_gabung = date('ym', strtotime(Carbon::now()));
        $countuser = User::count();
        $nik_karyawan = $tgl_gabung . $countuser . $level_id.$request->data__site_id; #FORMAT = th-bulan-urutan karyawan,level,site
        $data['id'] = $nik_karyawan;
        $data['corporate_id'] = Session::get('corp_id');
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = ucwords($request->alamat_lengkap);
        $data['name'] = ucwords($request->name);
        $data['password'] = Hash::make($request->password);
        $data['data__site_id'] = $request->data__site_id;
        $data['tgl_gabung'] = date('Y-m-d', strtotime(Carbon::now()));
        $data['photo'] = 'user.png';
        $data['status_user'] = 'Enable';

        $datarole['role_id'] = $level_id;
        $datarole['model_type'] = 'App\Models\User';
        $datarole['model_id'] = $nik_karyawan;

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

        $notifikasi = [
            'pesan' => 'Data' . $request->name . ' Berhasil dibuat',
            'alert' => 'success',
        ];
        return redirect()->route('admin.user.index')->with($notifikasi);
    }

    function edit(Request $request, $id)
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
            'level' => 'required',
            'file' => 'max:1000|mimes:jpeg',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'ktp.required' => 'Nomor Ktp tidak boleh kosong.',
            'hp.required' => 'Nomor Hp tidak boleh kosong.',
            'hp.unique' => 'Nomor Hp sudah terdaftar.',
            'email.required' => 'Email tidak boleh kosong',
            'alamat_lengkap.required' => 'Alamat tidak boleh kosong',
            'data__site_id.required' => 'Site tidak boleh kosong',
            'level.required' => 'Level tidak boleh kosong',
            'file.max' => 'Ukuran foto terlalu besar',
            'file.mimes' => 'Format hanya bisa jpeg',
        ]
    );
 if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);
 
//  dd($request->level);
        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $datarole['role_id'] = $level_id;

        $photo = $request->file('file');
        if ($photo) {

            $filename = Session::get('corp_id').$id.'.jpeg';
            $path = 'image/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
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

        Model_Has_Role::where('model_id', $id)->update($datarole);
        User::whereId($id)->update($data);

        $notifikasi = [
            'pesan' => 'Data' . $request->name . ' Berhasil dirubah',
            'alert' => 'success',
        ];
        return redirect()->route('admin.user.index')->with($notifikasi);
    }
}
