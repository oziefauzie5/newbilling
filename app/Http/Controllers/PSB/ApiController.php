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
    public function Api_payment_ftth($data_pelanggan,$nama_user,$reg)
    {
        $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
        $user = $data_pelanggan->router_username;
        $pass = $data_pelanggan->router_password;
        $API = new RouterosAPI();
        // return response()->json($data_pelanggan);
            $API->debug = false;

            if ($data_pelanggan->reg_layanan == 'PPP') {
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ppp/secret/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'profile' =>  $data_pelanggan->paket_nama == '' ? '' : $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],


                            'disabled' => 'no',
                        ]);
                        $cek_status = $API->comm('/ppp/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ppp/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                            return 0;
                            #success
                            #Berhasil melakukan pembayaran
                            
                        } else {
                            return 0;
                            #success
                            #Berhasil melakukan pembayaran. Pelanggan sedang tidak aktif
                        }
                    } else {  
                        #Jika pelanggan tidak ditemukan pada router
                        $API->comm('/ppp/secret/add', [
                            'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                            'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                            'service' => 'pppoe',
                            'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                            'disabled' => 'no',
                        ]);
                        return 0;
                            #success (jika pelanggan tidak ditemukan pada router)
                            #Berhasil melakukan pembayaran.
                    }
                } else {
                        return 3;
                            #failed (jika router disconnect)
                }
            } else {
                #JIKA PAYMENT UNTUK HOTSPOT
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'profile' => $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                            'disabled' => 'no',
                        ]);
                        return 0;
                    } else {
                        $API->comm('/ip/hotspot/user/add', [
                            'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                            'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                            'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                            'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                            'disabled' => 'no',
                        ]);
                        return 0;
                            #success (jika pelanggan tidak ditemukan pada router)
                            #Berhasil melakukan pembayaran.
                    }
                } else {
                   return 3;
                            #failed (jika router disconnect)
                }
            }
    }
}
