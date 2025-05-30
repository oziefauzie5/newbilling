<?php

namespace Database\Seeders;

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Mitra\MitraSetting;
use App\Models\PSB\FtthFee;
use App\Models\PSB\FtthInstalasi;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Teknisi\Data_Odc;
use App\Models\Teknisi\Data_Odp;
use App\Models\Teknisi\Data_Olt;
use App\Models\Teknisi\Data_pop;
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
        
         Corporate::updateorcreate([
            'id' => '240110001',
            'corp_app_id' => '1',
            'corp_url' => 'http://appbill.test',
        ]);
        
        SettingAplikasi::updateorcreate(
            [
                'id' => '1',
                'corporate_id' => '240110001',
                'app_nama' => 'APPBILL',
                'app_brand' => 'APPBILL',
                'app_alamat' => 'Jl. Tampomas Alam Tirta Lestari, Pagelaran, Ciomas, Kab. Bogor',
            ]
        );
        Data_Site::updateorcreate([
            'id'=> '1',
            'corporate_id' => '240110001',
            'site_prefix'=> 'BGR',
            'site_nama'=> 'BOGOR',
            'site_status'=> 'Enable',
        ]);
        
        
        Data_pop::updateorcreate([
            'corporate_id' => '240110001',
            'data__Site_id' => '1',
            'pop_nama' => 'POP Ciomas',
            'pop_alamat' => 'Alam Tita',
            'pop_koordinat' => '415215212',
            'pop_file_topologi' => 'pop.pdf',
            'pop_status' => 'Enable',
        ]);
        Data_pop::updateorcreate([
            'corporate_id' => '240110001',
            'data__Site_id' => '1',
            'pop_nama' => 'POP Cilebut',
            'pop_alamat' => 'Jembatan 3',
            'pop_koordinat' => '415215212',
            'pop_file_topologi' => 'pop.pdf',
            'pop_status' => 'Enable',
        ]);
        Router::updateorcreate([
            'corporate_id'=> '240110001',
            'data_pop_id'=> '1',
            'router_nama'=> 'RB450',
            'router_ip'=> '103.171.83.159',
            'router_dns'=> '103.171.82.82,103.171.83.82',
            'router_port_api'=> '9924',
            'router_port_remote'=> '8889',
            'router_username'=> 'ovallnet122',
            'router_password'=> '@Fauzi12234',
            'router_status'=> 'Enable',
        ]);
        Router::updateorcreate([
            'corporate_id'=> '240110001',
            'data_pop_id'=> '1',
            'router_nama'=> 'Dist.ATL',
            'router_ip'=> '103.171.83.159',
            'router_dns'=> '103.171.82.82,103.171.83.82',
            'router_port_api'=> '9924',
            'router_port_remote'=> '8889',
            'router_username'=> 'ovallnet122',
            'router_password'=> '@Fauzi12234',
            'router_status'=> 'Enable',
        ]);
        Router::updateorcreate([
            'corporate_id'=> '240110001',
            'data_pop_id'=> '1',
            'router_nama'=> 'DIST.KotaBatu',
            'router_ip'=> '103.171.83.159',
            'router_dns'=> '103.171.82.82,103.171.83.82',
            'router_port_api'=> '9924',
            'router_port_remote'=> '8889',
            'router_username'=> 'ovallnet122',
            'router_password'=> '@Fauzi12234',
            'router_status'=> 'Enable',
        ]);
        Router::updateorcreate([
            'corporate_id'=> '240110001',
            'data_pop_id'=> '1',
            'router_nama'=> 'DIST.Babakan',
            'router_ip'=> '103.171.83.159',
            'router_dns'=> '103.171.82.82,103.171.83.82',
            'router_port_api'=> '9924',
            'router_port_remote'=> '8889',
            'router_username'=> 'ovallnet122',
            'router_password'=> '@Fauzi12234',
            'router_status'=> 'Enable',
        ]);
        Data_Olt::updateorcreate([
            'corporate_id'=>'240110001',
            'router_id'=>'1',
            'olt_nama'=>'Vsol',
            'olt_pon'=>'4',
            'olt_file_topologi'=>'-',
            'olt_status'=>'Enable',
        ]);
        Data_Olt::updateorcreate([
            'corporate_id'=>'240110001',
            'router_id'=>'1',
            'olt_nama'=>'Hsgq',
            'olt_pon'=>'4',
            'olt_file_topologi'=>'-',
            'olt_status'=>'Enable',
        ]);
        Data_Olt::updateorcreate([
            'corporate_id'=>'240110001',
            'router_id'=>'1',
            'olt_nama'=>'Vsol',
            'olt_pon'=>'4',
            'olt_file_topologi'=>'-',
            'olt_status'=>'Enable',
        ]);
        Data_Odc::updateorcreate([
            'corporate_id' =>'240110001',
            'odc_id' =>'1.1',
            'data__olt_id' =>'1',
            'odc_pon_olt' =>'1',
            'odc_core' =>'12',
            'odc_nama' =>'ODC Pengkolan',
            'odc_jumlah_port' =>'8',
            'odc_file_topologi' =>'-',
            'odc_lokasi_img' =>'-',
            'odc_koordinat' =>'123132',
            'odc_keterangan' =>'aaaa',
            'odc_status' =>'Enabale',
        ]);
        Data_Odp::updateorcreate([
            'corporate_id' =>'240110001',
            'data__odc_id' =>'1',
            'odp_id' =>'1.1.1',
            'odp_core' =>'1',
            'odp_nama' =>'Odp Cengli',
            'odp_jumlah_slot' =>'8',
            'odp_lokasi_img' =>'--',
            'odp_file_topologi' =>'--',
            'odp_koordinat' =>'--',
            'odp_keterangan' =>'Odp dekat rumah cengli',
            'odp_status' =>'Enable',
            'odp_slot_odc' =>'1',
        ]);
        InputData::updateorcreate([
            'id' =>'1001',
            'corporate_id' =>'240110001',
            'data__site_id' =>'1',
            'input_tgl' =>'2025-01-01',
            'input_nama' =>'Fauzi',
            'input_ktp' =>'3200001',
            'input_hp' =>'85770273898',
            'input_hp_2' =>'81386987015',
            'input_email' =>'oziefauzie31@gmail.com',
            'input_alamat_ktp' =>'Kp. Cibinong RT 02/01 Sukaharja, Ciomas Kab. Bogor',
            'input_alamat_pasang' =>'Kp. Cibinong RT 02/01 Sukaharja, Ciomas Kab. Bogor',
            'input_sales' =>'0',
            'input_subseles' =>'Ayu',
            'password' =>'85770273898',
            'input_maps' =>'---',
            'input_koordinat' =>'---',
            'input_status' =>'INPUT DATA',
            'input_keterangan' =>'-',
        ]);




        SettingBiaya::updateorcreate(
            [
                'corporate_id' => '240110001',
                'biaya_pasang' => '0',
                'biaya_ppn' => '11',
                'biaya_psb' => '0',
                'biaya_sales' => '0',
                'biaya_bph_uso' => '0',
            ]
        );
       

        SettingWaktuTagihan::updateorcreate([
            'corporate_id' => '240110001',
            'wt_jeda_isolir_hari' =>'2',
            'wt_jeda_tagihan_pertama' =>'2',
        ]);
        Paket::updateorcreate([
            'paket_id'=>'1',
            'corporate_id' => '240110001',
            'paket_nama'=> 'SIMULASI APPBILL 10 MBPS',
            'paket_limitasi'=> '5M/5M',
            'paket_shared'=> '1',
            'paket_masa_aktif'=> '30',
            'paket_harga'=> '150000',
            'paket_komisi'=> '0',
            'paket_lokal'=> '192.168.100.1',
            'paket_remote_address'=> '0',
            'paket_status'=> 'Enable',
            'paket_layanan'=> 'PPP',
            'paket_mode'=> '0',
            'paket_warna'=> '0',
        ]);

       



        

        User::updateorcreate([
            'id' => '1',
            'corporate_id' => '240110001',
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'ktp' => '000',
            'hp' => '008',
            'alamat_lengkap' => 'Bogor',
            'status_user' => 'Enable',
            'data__site_id' => '1',
            'photo' => 'user.png',
            'password' => Hash::make('1234'),
        ]);
        

        User::updateorcreate([
            'id' => '2',
            'corporate_id' => '240110001',
            'name' => 'SYSTEM',
            'email' => 'system@gmail.com',
            'username' => 'system',
            'ktp' => '00',
            'hp' => '00',
            'data__site_id' => '1',
            'photo' => 'user.png',
            'alamat_lengkap' => '',
            'status_user' => 'Enable',
            'password' => Hash::make('1234'),
        ]);
        User::updateorcreate([
            'id' => '10',
            'corporate_id' => '240110001',
            'name' => 'TRIPAY',
            'data__site_id' => '1',
            'photo' => 'user.png',
            'status_user' => 'Enable',
        ]);
        // User::updateorcreate([
        //     'id' => '25053151',
        //     'corporate_id' => '240110001',
        //     'name' => 'Ricki Baihaki',
        //     'email' => 'ricki@gmail.com',
        //     'username' => 'ricki',
        //     'ktp' => '000',
        //     'hp' => '008',
        //     'alamat_lengkap' => 'Ciomas Permai D11 No 14',
        //     'status_user' => 'Enable',
        //     'data__site_id' => '1',
        //     'photo' => 'user.png',
        //     'password' => Hash::make('1234'),
        // ]);
        // MitraSetting::updateorcreate([
        //     'corporate_id' => '240110001',
        //     'mts_user_id' =>'25053151',
        //     'data__site_id' =>'1',
        //     'mts_limit_minus' =>'0',
        //     'mts_komisi' =>'10000',
        // ]);
        // Registrasi::updateorcreate([
        //     'reg_idpel' =>'1001',
        //     'corporate_id' => '240110001',
        //     'reg_profile' => '1',
        //     'reg_nolayanan' => '2505100125',
        //     'reg_layanan' => 'ppp',
        //     'reg_jenis_tagihan' => 'prabayar',
        //     'reg_harga' => '15000',
        //     'reg_ppn' => '15500',
        //     'reg_bph_uso' => '4000',
        //     'reg_kode_unik' => '0',
        //     'reg_inv_control' => '1',
        //     'reg_tgl_pasang' => '2025-05-25',
        //     'reg_tgl_jatuh_tempo' => '2025-06-25',
        //     'reg_tgl_tagih' => '2025-06-23',
        //     'reg_tgl_deaktivasi' => '',
        //     'reg_img' => '--',
        //     'reg_catatan' => '--',
        //     'reg_status' => 'UNPAID',
        //     'reg_progres' => '0',
        //      'reg_username' => 'Fauzi',
        //     'reg_password' => '1234567',
        // ]);
        // FtthInstalasi::updateorcreate([
        //     'id' =>'1001',
        //     'corporate_id' => '240110001',
        //     'reg_router' => '1',
        //     'data__odp_id' => '1',
        //     'reg_out_odp' => '16,52',
        //     'reg_in_ont' => '16,53',
        //     'reg_los_opm' => '0,1',
        //     'reg_slot_odp' => '1',
           
        // ]);
        // FtthFee::updateorcreate([
        //     'fee_idpel' =>'1001',
        //     'corporate_id' => '240110001',
        //     'reg_mitra' => '25053151',
        //     'reg_fee' => '15000',
        // ]);

        
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
        // $role_mitra = Role::updateorcreate(
        //     ['name' => 'PIC'],
        //     ['guard_name' => 'web'],
        //     ['id' => '15'],
        //     ['name' => 'PIC'],
        //     ['guard_name' => 'web']
        // );
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
        // $permission3 = Permission::updateorcreate(
        //     ['name' => 'PIC'],
        //     ['guard_name' => 'web'],
        //     ['id' => '15'],
        //     ['name' => 'PIC'],
        //     ['guard_name' => 'web'],
        // );

        $role_admin->givePermissionTo($permission);
        $role_system->givePermissionTo($permission2);
        // $role_mitra->givePermissionTo($permission3);

        $admin = User::find(1);
        $system = User::find(2);
        $system = User::find(10);
        // $mitra = User::find(25053151);

        $admin->assignRole('admin');
        $system->assignRole('SYSTEM');
        // $mitra->assignRole('PIC');
    }
}
