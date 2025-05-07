<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotspot\Data_Voucher;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use Carbon\Carbon;

class HotspotAPIController extends Controller
{
   public function store_vhc($pesanan_id,$paket_id)
   {
       $query = Data_Voucher::where('vhc_pesananid',$pesanan_id)->where('vhc_paket',$paket_id)
       ->join('routers','routers.id','=','data__vouchers.vhc_router')
       ->join('pakets','pakets.paket_id','=','data__vouchers.vhc_paket')
       ->join('data__outlets','data__outlets.outlet_id','=','data__vouchers.vhc_outlet')
       ->first();
       
       $query_vc = Data_Voucher::where('vhc_pesananid',$pesanan_id)->where('vhc_paket',$paket_id)
       ->join('pakets','pakets.paket_id','=','data__vouchers.vhc_paket')
       ->get();

    //    dd($query_vc);
       
       $ip =   $query->router_ip . ':' . $query->router_port_api;
       $user = $query->router_username;
       $pass = $query->router_password;
       $API = new RouterosAPI();
       $API->debug = false;
       $commt =  "vc-".$query->vhc_id . "-" . date('d-m-Y', strtotime(Carbon::now())) . "-" . $query->outlet_nama;
       if ($API->connect($ip, $user, $pass)) {
        $secret = $API->comm('/ip/hotspot/user/profile/print', [
            '?name' => $query->paket_nama,
        ]);
        // dd($query_vc);
            if($secret){
                foreach ($query_vc as $qv) {      
                    
                    // echo $qv->paket_nama;
                    $API->comm('/ip/hotspot/user/add', [
                        'name' => $qv->vhc_username == '' ? '' : $qv->vhc_username,
                        'password' => $qv->vhc_password  == '' ? '' : $qv->vhc_password,
                        'profile' => $qv->paket_nama  == '' ? 'default' : $qv->paket_nama,
                        'comment' => $commt == '' ? '' : $commt,
                        'disabled' => 'no',
                    ]);  
                }
                // dd('test');
                $notif = 'notif1';
                return $notif ;
            } else {
                $notif = 'notif2';
                return $notif ;
            }
        } else {
            $notif = 'notif3';
            return $notif ;
        }
    
   }
   public function cek_data_update_voucher()
   {
       $query = Router::where('id',17)->first();

       $ip =   $query->router_ip . ':' . $query->router_port_api;
       $user = $query->router_username;
       $pass = $query->router_password;
       $API = new RouterosAPI();
       $API->debug = false;
       if ($API->connect($ip, $user, $pass)) {
        $secret = $API->comm('/ip/hotspot/user/print');
        return $secret;
        } else {
            $notif = 'notif3';
            return $notif ;
        }
    
        
   }
   public function update_data_voucher($id)
   {
       $query = Router::where('id',17)->first();

       $ip =   $query->router_ip . ':' . $query->router_port_api;
       $user = $query->router_username;
       $pass = $query->router_password;
       $API = new RouterosAPI();
       $API->debug = false;
       if ($API->connect($ip, $user, $pass)) {
        $secret = $API->comm('/ip/hotspot/user/print', [
            '?comment' => $id,
        ]);
        foreach ($secret as $key) {
            echo $key['name'];
        }
        // dd($secret);
        // return $secret;
        } else {
            $notif = 'notif3';
            return $notif ;
        }
    
        
   }

   public function list_voucher_terjual()
   {
        $query = Router::where('id',17)->first();
        // $data_voucher = Data_Voucher::get();

        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
            if ($API->connect($ip, $user, $pass)) {
                $data['getData'] = $API->comm("/system/script/print", array(
                    "?comment" => "fisnet",
                ));
                $data['TotalReg'] = count($data['getData']);
               
                return $data;
               
            } else {
                return 'Disconet bro';
            }
    
   }
  

   public function detail_voucherapi($username)
   {
        $query = Data_Voucher::join('routers','routers.id','=','data__vouchers.vhc_router')
        ->where('vhc_username', $username)->first();
        
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($ip, $user, $pass)) {
                $data['user'] = $API->comm("/ip/hotspot/user/print", array(
                    "?name" => $query->vhc_username,
                ));
                
                #vhc_durasi_pakai
                if($data['user'])
                {
                    Data_Voucher::where('vhc_username',$query->vhc_username)->update([
                        'vhc_durasi_pakai'=>  $data['user'][0]['uptime'],
                    ]);
                }
                $data['active'] = $API->comm("/ip/hotspot/active/print", array(
                    "?user" => $query->vhc_username,
                ));
                $data['router'] = $query->vhc_router;

                return $data;
               
            } else {
                return 'Disconet bro';
            }
            // return $query;
    }
   public function kick_voucherapi($username)
   {
        $query = Data_Voucher::join('routers','routers.id','=','data__vouchers.vhc_router')
        ->where('vhc_username', $username)->first();
        
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($ip, $user, $pass)) {
                $getcookies = $API->comm("/ip/hotspot/cookie/print", array(
                    "?user" => $query->vhc_username,
                ));
                $TotalReg = count($getcookies);
                if($getcookies){
                    for ($i = 0; $i < $TotalReg; $i++) {
                        $cookies = $getcookies[$i];
                        $id = $cookies['.id'];
                        $user = $cookies['user'];
                        $maca = $cookies['mac-address'];
                        // echo $user;
                        $API->comm("/ip/hotspot/cookie/remove", array(
                            ".id" => "$id",
                        ));
                    }
                }
                $user = $API->comm("/ip/hotspot/active/print", array(
                    "?user" => $query->vhc_username,
                ));

                if($user){
                    $API->comm('/ip/hotspot/active/remove', [
                        '.id' =>  $user[0]['.id'],
                    ]);
                }
                return $user;
               
            } else {
                return 'Disconet bro';
            }
            // return $query;
    }
   public function reset_voucherapi($username)
   {
        $query = Data_Voucher::join('routers','routers.id','=','data__vouchers.vhc_router')
        ->join('data__outlets','data__outlets.outlet_id','=','data__vouchers.vhc_outlet')
        ->join('pakets','pakets.paket_id','=','data__vouchers.vhc_paket')
        ->where('vhc_username', $username)->first();

        $commt =  "vc-".$query->vhc_id . "-" . date('d-m-Y', strtotime(Carbon::now())) . "-" . $query->outlet_nama;
        
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($ip, $user, $pass)) {
                $getcookies = $API->comm("/ip/hotspot/cookie/print", array(
                    "?user" => $query->vhc_username,
                ));
                $TotalReg = count($getcookies);
                if($getcookies){
                    for ($i = 0; $i < $TotalReg; $i++) {
                        $cookies = $getcookies[$i];
                        $id = $cookies['.id'];
                        $user = $cookies['user'];
                        $maca = $cookies['mac-address'];
                        // echo $user;
                        $API->comm("/ip/hotspot/cookie/remove", array(
                            ".id" => "$id",
                        ));
                    }
                }
                $user = $API->comm("/ip/hotspot/active/print", array(
                    "?user" => $query->vhc_username,
                ));

                if($user){
                    $API->comm('/ip/hotspot/active/remove', [
                        '.id' =>  $user[0]['.id'],
                    ]);
                }

                $get_user_reset = $API->comm("/ip/hotspot/user/print", array(
                    "?name" => $query->vhc_username,
                ));
                if($get_user_reset){
                    $API->comm('/ip/hotspot/user/reset-counters', [
                        '.id' =>  $get_user_reset[0]['.id'],
                    ]);
                } 
                if($get_user_reset){
                    $API->comm('/ip/hotspot/user/set', [
                        '.id' =>  $get_user_reset[0]['.id'],
                        'limit-uptime' =>  '00:00:00',
                        'comment' => $commt == '' ? '' : $commt,
                    ]);
                }else {
                    $API->comm('/ip/hotspot/user/add', [
                        'name' => $query->vhc_username == '' ? '' : $query->vhc_username,
                        'password' => $query->vhc_password  == '' ? '' : $query->vhc_password,
                        'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                        'comment' => $commt == '' ? '' : $commt,
                        'disabled' => 'no',
                    ]);
                }
                $get_userscript = $API->comm("/system/script/print", array(
                    "?name" => $query->vhc_script,
                ));
                if( $get_userscript){
                    $API->comm('/system/script/remove', [
                        '.id' =>  $get_userscript[0]['.id'],
                    ]);
                }
                Data_Voucher::where('vhc_username',$query->vhc_username)->update([
                    'vhc_tgl_jual' => '',
                    'vhc_mac' =>  '',
                    'vhc_status_pakai' => 'Belum Terpakai',
                    'vhc_exp' => '',
                    'vhc_script' => '',
                ]);
                return ;
               
            } else {
                return 'Disconet bro';
            }
            // return $query;
    }

    public function store_updatevhc()
    {
        $query = Router::where('id',17)->first();
        // dd($query);
        $query_vc = Data_Voucher::where('vhc_status_pakai','Terpakai')
        ->join('pakets','pakets.paket_id','=','data__vouchers.vhc_paket')
        ->join('data__outlets','data__outlets.outlet_id','=','data__vouchers.vhc_outlet')
        ->get();


 
        
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($ip, $user, $pass)) {
            
            foreach ($query_vc as $qv) {      
                     $commt =  "vc-".$qv->vhc_id . "-" . date('d-m-Y', strtotime(Carbon::now())) . "-" . $qv->outlet_nama;
                     
                     // echo $qv->paket_nama;
                     $API->comm('/ip/hotspot/user/add', [
                         'name' => $qv->vhc_username == '' ? '' : $qv->vhc_username,
                         'password' => $qv->vhc_password  == '' ? '' : $qv->vhc_password,
                         'profile' => $qv->paket_nama  == '' ? 'default' : $qv->paket_nama,
                         'comment' => $commt == '' ? '' : $commt,
                         'disabled' => 'no',
                     ]);  
                 }
                 $notif = 'notif1';
                 return $notif ;

         } else {
             $notif = 'notif3';
             return $notif ;
         }
     
    }
}
