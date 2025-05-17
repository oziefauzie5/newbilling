<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi\Data_Site;
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

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|noc']);
    }

    public function index()
    {
        $data['data_user'] = User::select('users.*', 'data__sites.*', 'roles.name as level', 'roles.id as role_id')
            ->join('data__sites', 'data__sites.site_id', '=', 'users.user_site')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->get();
        $data['role'] = Role::where('id','!=','13')->get();
        $data['data_site'] = Data_Site::where('site_status', 'Enable')->get();
        return view('User/index', $data);
    }

    public function store(Request $request)
    {
        $validator = FacadesValidator::make(
            $request->all(),
            [
                'name' => 'required',
                'ktp' => 'required',
                'hp' => 'required|unique:users',
                'email' => 'required',
                'alamat_lengkap' => 'required',
                'user_site' => 'required',
                'username' => 'unique:users',
                'password' => 'required',
                'level' => 'required',
            ],
            [
                'name' => 'Nama tidak boleh kosong.',
                'name' => 'Nomor Ktp tidak boleh kosong.',
                'hp.unique' => 'Nomor Hp sudah terdaftar.',
                'email' => 'Email tidak boleh kosong',
                'alamat_lengkap' => 'Alamat tidak boleh kosong',
                'user_site' => 'Site tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan.',
                'password' => 'Password tidak boleh kosong',
                'level' => 'Level tidak boleh kosong',
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
        // dd($cek_role);
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
        // $data_site = Data_Site::where('')();
        $nik_karyawan = $tgl_gabung . $countuser . $level_id.$request->user_site; #FORMAT = th-bulan-urutan karyawan,level,site
        // dd($request->user_site);
        $data['id'] = $nik_karyawan;
        $data['email'] = $request->email;
        $data['username'] = $request->username;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = ucwords($request->alamat_lengkap);
        $data['name'] = ucwords($request->name);
        $data['password'] = Hash::make($request->password);
        $data['user_site'] = $request->user_site;
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
            'pesan' => 'Berhasil!! Data' . $request->name . ' Berhasil dibuat',
            'alert' => 'success',
        ];
        return redirect()->route('admin.user.index')->with($notifikasi);
    }

    public function edit(Request $request, $id)
    {

        $nomorhp = (new ConvertNoHp())->convert_nohp($request->hp);
        $get =  explode("|", $request->level);
        $level_id = $get[0];
        $datarole['role_id'] = $level_id;

        $photo = $request->file('file');
        if ($photo) {

            $filename = $id.'.png';
            $path = 'photo-user/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $data['photo'] = $filename;
        }

        $data['email'] = $request->email;
        $data['ktp'] = $request->ktp;
        $data['hp'] = $nomorhp;
        $data['alamat_lengkap'] = ucwords($request->alamat_lengkap);
        $data['name'] = ucwords($request->name);
        $data['username'] = $request->username;
        $data['user_site'] = $request->user_site;
        $data['status_user'] = $request->status_user;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        Model_Has_Role::where('model_id', $id)->update($datarole);
        User::whereId($id)->update($data);

        $notifikasi = [
            'pesan' => 'Berhasil!! Data' . $request->name . ' Berhasil dirubah',
            'alert' => 'success',
        ];
        return redirect()->route('admin.user.index')->with($notifikasi);
    }
}
