<?php

namespace App\Imports;

use App\Models\Model_Has_Role;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport  implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        //  return new Role([
        //     'id' =>$row[0],
        //     'name'=>$row[1],
        //     'guard_name'=>$row[2],                                                                            
        // ]);
        // return new User([
        //     'id' =>$row[0],
        //     'corporate_id' =>Session::get('corp_id'),
        //     'name'=>$row[1],
        //     'email'=>$row[2],
        //     'password'=>$row[3],
        //     'username'=>$row[4],
        //     'ktp'=>$row[5],
        //     'hp'=>$row[6],
        //     'photo'=>$row[7],
        //     'data__site_id'=>$row[8],
        //     'alamat_lengkap'=>$row[9],
        //     'status_user'=>$row[10],                                                                              
        //     'created_at'=>$row[11],                                                                              
        //     'updated_at'=>$row[12],                                                                              
        // ]);
       
        // return new Permission([
        //     'id' =>$row[0],
        //     'name'=>$row[1],
        //     'guard_name'=>$row[2],                                                                            
        // ]);
        // return new RoleHasPermission([
        //     'permission_id' =>$row[0],
        //     'role_id'=>$row[1],
        // ]);
        // return new Model_Has_Role([
        //     'role_id' =>$row[0],
        //     'model_type'=>$row[1],
        //     'model_id'=>$row[2],
        // ]);
        
    }
}
