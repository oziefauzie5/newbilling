<?php

namespace Database\Seeders;

use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Router\Router;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {

        SettingAplikasi::create(
            [
                'app_nama' => 'PT OVALL SOLUSINDO MANDIRI',
                'app_brand' => 'OVALL FIBER',
                'app_alamat' => 'Jl. Tampomas Alam Tirta Lestari D05 No006, Pagelaran, Ciomas, Kab. Bogor',
            ]
        );
        Router::create(
            [
                'router_nama' => 'CIOMAS CCR',
                'router_ip' => '103.171.83.243',
                'router_dns' => '103.171.82.82,103.171.83.82',
                'router_port_api' => '5528',
                'router_port_remote' => '8889',
                'router_username' => 'ovallnet122',
                'router_password' => '@Fauzi12234',
                'router_status' => 'Enable',
            ]
        );
        Router::create(
            [
                'router_nama' => 'CIOMAS X8',
                'router_ip' => '103.171.83.241',
                'router_dns' => '103.171.82.82,103.171.83.82',
                'router_port_api' => '5532',
                'router_port_remote' => '8889',
                'router_username' => 'ovallnet122',
                'router_password' => '@Fauzi12234',
                'router_status' => 'Enable',
            ]
        );
        Router::create(
            [
                'router_nama' => 'CILEBUT RB1100-1',
                'router_ip' => '202.73.24.78',
                'router_dns' => '49.0.0.49,49.0.0.94',
                'router_port_api' => '1327',
                'router_port_remote' => '8889',
                'router_username' => 'ovallnet122',
                'router_password' => '@Fauzi12234',
                'router_status' => 'Enable',
            ]
        );
        Router::create(
            [
                'router_nama' => 'CILEBUT RB1100-2',
                'router_ip' => 'remote.rlradius.com',
                'router_dns' => '1.1.1.1,1.1.1.1',
                'router_port_api' => '3293',
                'router_port_remote' => '3292',
                'router_username' => 'ovallnet122',
                'router_password' => '@Fauzi12234',
                'router_status' => 'Enable',
            ]
        );
      

        SettingBiaya::create(
            [
                'biaya_pasang' => '0',
                'biaya_ppn' => '11',
                'biaya_psb' => '20000',
                'biaya_sales' => '50000',
                'biaya_deposit' => '100000',
                'biaya_kas' => '5000',
                'biaya_kerjasama' => '10000',
            ]
        );


        User::create([
            'id' => '1',
            'name' => 'ACHMAD FAUZI',
            'email' => 'Oziefauzie31@gmail.com',
            'username' => 'admin',
            'ktp' => '320122947928334',
            'hp' => '085770273898',
            'alamat_lengkap' => 'Kp. Cibinong RT02/01 Sukaharja, Ciomas, Bogor',
            'status_user' => 'Aktif',
            'password' => Hash::make('1234'),
        ]);
        User::create([
            'id' => '2',
            'name' => 'SYSTEM',
            'email' => 'system@gmail.com',
            'username' => 'system',
            'ktp' => '00',
            'hp' => '00',
            'alamat_lengkap' => '',
            'status_user' => 'Aktif',
            'password' => Hash::make('1234'),
        ]);
        User::create([
            'id' => '10',
            'name' => 'TRIPAY',
        ]);

        $role_admin = Role::updateorcreate(
            ['name' => 'admin'],
            ['guard_name' => 'web'],
            ['id' => '1'],
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        $role_system = Role::updateorcreate(
            ['name' => 'SYSTEM'],
            ['guard_name' => 'web'],
            ['id' => '1'],
            ['name' => 'SYSTEM'],
            ['guard_name' => 'web']
        );
        $permission = Permission::updateorcreate(
            ['name' => 'admin'],
            ['guard_name' => 'web'],
            ['id' => '1'],
            ['name' => 'admin'],
            ['guard_name' => 'web'],
        );
        $permission2 = Permission::updateorcreate(
            ['name' => 'SYSTEM'],
            ['guard_name' => 'web'],
            ['id' => '2'],
            ['name' => 'SYSTEM'],
            ['guard_name' => 'web'],
        );

        $role_admin->givePermissionTo($permission);
        $role_system->givePermissionTo($permission2);

        $admin = User::find(1);
        $system = User::find(2);
        $system = User::find(10);

        $admin->assignRole('admin');
        $system->assignRole('SYSTEM');
    }
}
