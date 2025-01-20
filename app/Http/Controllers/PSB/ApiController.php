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
            $secret = $API->comm('/ppp/secret/print', [
                '?name' => $query->reg_username,
            ]);
            if ($secret) {
                $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'comment' => 'Aktivasi' == '' ? '' : 'Aktivasi',
                    'disabled' => 'no',
                ]);
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
            $secret = $API->comm('/ip/hotspot/user/print', [
                '?name' => $query->reg_username,
            ]);
            if ($secret) {
                $API->comm('/ip/hotspot/user/set', [
                    '.id' => $secret[0]['.id'],
                    'comment' => 'Aktivasi' == '' ? '' : 'Aktivasi',
                    'disabled' => 'no',
                ]);
                return '0';
            } else {
                return '1';
            }
        } else {
            return '2';
        }
    }
}
