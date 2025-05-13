<?php

namespace Database\Seeders;

use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
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


        // SettingAplikasi::create(
        //     [
        //         'app_nama' => 'PT OVALL SOLUSINDO MANDIRI',
        //         'app_brand' => 'OVALL FIBER',
        //         'app_alamat' => 'Jl. Tampomas Alam Tirta Lestari D05 No006, Pagelaran, Ciomas, Kab. Bogor',
        //     ]
        // );
        // Router::create(
        //     [
        //         'router_nama' => 'CIOMAS CCR',
        //         'router_ip' => '103.171.83.243',
        //         'router_dns' => '103.171.82.82,103.171.83.82',
        //         'router_port_api' => '5528',
        //         'router_port_remote' => '8889',
        //         'router_username' => 'ovallnet122',
        //         'router_password' => '@Fauzi12234',
        //         'router_status' => 'Enable',
        //     ]
        // );
        // Router::create(
        //     [
        //         'router_nama' => 'CIOMAS X8',
        //         'router_ip' => '103.171.83.241',
        //         'router_dns' => '103.171.82.82,103.171.83.82',
        //         'router_port_api' => '5532',
        //         'router_port_remote' => '8889',
        //         'router_username' => 'ovallnet122',
        //         'router_password' => '@Fauzi12234',
        //         'router_status' => 'Enable',
        //     ]
        // );
        // Router::create(
        //     [
        //         'router_nama' => 'CILEBUT RB1100-1',
        //         'router_ip' => '202.73.24.78',
        //         'router_dns' => '49.0.0.49,49.0.0.94',
        //         'router_port_api' => '1327',
        //         'router_port_remote' => '8889',
        //         'router_username' => 'ovallnet122',
        //         'router_password' => '@Fauzi12234',
        //         'router_status' => 'Enable',
        //     ]
        // );
        // Router::create(
        //     [
        //         'router_nama' => 'CILEBUT RB1100-2',
        //         'router_ip' => 'remote.rlradius.com',
        //         'router_dns' => '1.1.1.1,1.1.1.1',
        //         'router_port_api' => '3293',
        //         'router_port_remote' => '3292',
        //         'router_username' => 'ovallnet122',
        //         'router_password' => '@Fauzi12234',
        //         'router_status' => 'Enable',
        //     ]
        // );


        // SettingBiaya::create(
        //     [
        //         'biaya_pasang' => '0',
        //         'biaya_ppn' => '11',
        //         'biaya_psb' => '20000',
        //         'biaya_sales' => '50000',
        //         'biaya_deposit' => '100000',
        //         'biaya_kas' => '5000',
        //         'biaya_kerjasama' => '10000',
        //     ]
        // );
        // Router::create(
        //     [
        //         'site_id' => '1',
        //         'site_nama' => 'Bekasi',
        //         'site_prefix' => 'BKS',
        //         'site_alamat' => 'Bekasi',
        //         'site_koordinat' => '-',
        //         'site_brand' => 'NETON',
        //         'site_keterangan' => '-',
        //         'site_status' => 'Enable',
        //         'router_port_api' => '5532',
        //         'router_port_remote' => '8889',
        //         'router_username' => 'ovallnet122',
        //         'router_password' => '@Fauzi12234',
        //         'router_status' => 'Enable',
        //     ]
        // );

        SettingWaktuTagihan::updateorcreate([
            'wt_jeda_isolir_hari' =>'2',
            'wt_jeda_tagihan_pertama' =>'2',
        ]);


        Data_Site::create([
            'site_id'=> '1',
            'site_prefix'=> 'BGR',
            'site_nama'=> 'BOGOR',
            'site_brand'=> 'OSM',
            'site_keterangan'=> '-',
            'site_status'=> 'Enable',
        ]);

        User::create([
            'id' => '1',
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'ktp' => '000',
            'hp' => '008',
            'alamat_lengkap' => 'Bogor',
            'status_user' => 'Enable',
            'user_site' => '1',
            'photo' => 'user.png',
            'password' => Hash::make('1234'),
        ]);

        User::create([
            'id' => '2',
            'name' => 'SYSTEM',
            'email' => 'system@gmail.com',
            'username' => 'system',
            'ktp' => '00',
            'hp' => '00',
            'user_site' => '1',
            'photo' => 'user.png',
            'alamat_lengkap' => '',
            'status_user' => 'Enable',
            'password' => Hash::make('1234'),
        ]);
        User::create([
            'id' => '10',
            'name' => 'TRIPAY',
            'user_site' => '1',
            'photo' => 'user.png',
            'status_user' => 'Enable',
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
