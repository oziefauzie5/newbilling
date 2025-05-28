<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router\RouterosAPI;

class ApiController extends Controller
{
    public function aktivasi_psb_ppp($query)
    {
        
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        
        if ($API->connect($ip, $user, $pass)) {
            $API->comm('/ip/pool/add', [
                'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
            ]);
            $API->comm('/ppp/profile/add', [
                'name' =>  $query->paket_nama == '' ? '' : $query->paket_nama,
                'rate-limit' => $query->paket_nama == '' ? '' : $query->paket_nama,
                'local-address' => $query->paket_lokal == '' ? '' : $query->paket_lokal,
                'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                'queue-type' => 'default-small' == '' ? '' : 'default-small',
                'dns-server' => $query->router_dns == '' ? '' : $query->router_dns,
                // 'disabled' => 'yes',
                'only-one' => 'yes',
            ]);
            
            $profile = $API->comm('/ppp/profile/print', [
                '?name' => $query->paket_nama,
            ]);
            if ($profile) {
                $API->comm('/ppp/secret/add', [
                    'name' => $query->reg_username == '' ? '' : $query->reg_username,
                    'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                    'service' => 'pppoe',
                    'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                    'comment' => 'REGISTRASI' == '' ? '' : 'REGISTRASI',
                    'disabled' => 'no',
                ]);
                // dd($ip .' '.$user.' '. $pass);
                return '0';
            } else {
                return '1';
            }
        } else {
            return '2';
        }
    }
    public function aktivasi_psb_hotspot($query)
    {
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $API->comm('/ip/hotspot/user/add', [
                'name' => $query->reg_username == '' ? '' : $query->reg_username,
                'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                'comment' => $query->input_nama  == '' ? '' : $query->input_nama,
                'disabled' => 'no',
            ]);
            return '0';
        } else {
            return '2';
        }
    }
}
