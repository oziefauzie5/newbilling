<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function role()
    {
        $data = array(
            'tittle' => 'ROLE',
            'datarole' => Role::all(),
        );
        return view('master/user/role', $data);
    }

    public function addrole(Request $request)
    {

        $get =  explode("|", $request->id);
        $id = $get[0];
        $level = $get[1];

        $data['id'] = $id;
        $data['name'] = $level;
        $data['guard_name'] = 'web';

        Role::create($data);
        Permission::create($data);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Role',
            'alert' => 'success',
        );
        return redirect()->route('admin.user.role')->with($notifikasi);
    }
    public function editrole(Request $request, $id)
    {

        $get =  explode("|", $request->id);
        $id = $get[0];
        $level = $get[1];

        $data['id'] = $id;
        $data['name'] = $level;
        $data['guard_name'] = 'web';

        Role::whereId($id)->update($data);
        Permission::whereId($id)->update($data);
        return redirect()->route('admin.user.role');
    }
    public function deleterole(Request $request, $id)
    {
        $data = Role::find($id);
        if ($data) {
            $data->delete();
        }
        $data = Permission::find($id);
        if ($data) {
            $data->delete();
        }
        return redirect()->route('admin.user.role');
    }
}
