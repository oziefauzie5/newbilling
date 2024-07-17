<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingBiaya;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaketVoucherController extends Controller
{
    public function store(Request $request)
    {
        Session::flash('paket_nama', $request->paket_nama);
        Session::flash('paket_komisi', $request->paket_komisi);
        Session::flash('paket_limitasi', $request->paket_limitasi);
        Session::flash('paket_harga', $request->paket_harga);
        Session::flash('paket_masa_aktif', $request->paket_masa_aktif);
        Session::flash('paket_durasi', $request->paket_durasi);
        Session::flash('paket_warna', $request->paket_warna);
        $request->validate([
            'paket_nama' => 'unique:pakets',
        ], [
            'paket_nama.unique' => 'Nama Paket tidak boleh sama',
        ]);
        // $paketreplace = str_replace(" ", "_", strtoupper($request->paket_nama));
        $data['paket_nama'] = $request->paket_nama;
        $data['paket_komisi'] = $request->paket_komisi;
        $data['paket_limitasi'] = $request->paket_limitasi;
        $data['paket_shared'] = $request->paket_shared;
        $data['paket_masa_aktif'] = $request->paket_masa_aktif;
        $data['paket_harga'] = $request->paket_harga;
        $data['paket_mode'] = $request->paket_mode;
        $data['paket_warna'] = $request->paket_warna;
        $data['paket_status'] = 'Enable';
        $data['paket_layanan'] = 'HOTSPOT';

        // $name = (preg_replace('/\s+/', '-', $data['paket_nama']));
        $name = $data['paket_nama'];
        $getsprice = '0'; #($paket_biaya_tagih);

        if ($data['paket_harga'] == "") {
            $price = "0";
        } else {
            $price = $data['paket_harga'];
        }
        if ($getsprice == "") {
            $sprice = "0";
        } else {
            $sprice = $getsprice;
        }
        if ($data['paket_komisi'] == "") {
            $sfee = "0";
        } else {
            $sfee = $data['paket_komisi'];
        }
        $getlock = 'Disable'; #($paket_kunci_user);
        if ($getlock == "Enable") {
            $lock = '; [:local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]]';
        } else {
            $lock = "";
        }

        $randstarttime = "0" . rand(1, 5) . ":" . rand(10, 59) . ":" . rand(10, 59);
        $randinterval = "00:02:" . rand(10, 59);

        $record = '; :local mac $"mac-address"; :local time [/system clock get time ]; /system script add name="$date-|-$time-|-$user-|-' . $price . '-|-$address-|-$mac-|-' . $data['paket_masa_aktif'] . '-|-' . $name . '-|-$comment-|-' . $sprice . '-|-' . $sfee . '" owner="$month$year" source=$date comment=fisnet';

        $mac_acak = '; :lokal mac $"mac-address";:lokal cekuser "cek-$user";:sys sch add name="cekuser" start-time=startup interval=00:00:05 on-even=":if ([ping $address count=5]=0) do=(:ip hotspot host remove [:ip hotspot host find mac-address=$mac];:ip dhcp-server lease remove[:ip dhcp-server lease find mac-address=$mac]; :system sch remove [:sys sch find name=$cekuser];)";';

        $onlogin = ':put (",' . $data['paket_mode'] . ',' . $price . ',' . $data['paket_masa_aktif'] . ',' . $sprice . ',,' . $getlock . ',"); {:local date [ /system clock get date ];:local year [ :pick $date 7 11 ];:local month [ :pick $date 0 3 ];:local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pic $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /sys sch add name="$user" disable=no start-date=$date interval="' . $data['paket_masa_aktif'] . '"; :delay 2s; :local exp [ /sys sch get [ /sys sch find where name="$user" ] next-run]; :local getxp [len $exp]; :if ($getxp = 15) do={ :local d [:pic $exp 0 6]; :local t [:pic $exp 7 16]; :local s ("/"); :local exp ("$d$s$year $t"); /ip hotspot user set comment=$exp [find where name="$user"];}; :if ($getxp = 8) do={ /ip hotspot user set comment="$date $exp" [find where name="$user"];}; :if ($getxp > 15) do={ /ip hotspot user set comment=$exp [find where name="$user"];}; /sys sch remove [find where name="$user"]';


        if ($data['paket_mode'] == "rem") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "remove";
        } elseif ($data['paket_mode'] == "ntf") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($data['paket_mode'] == "remc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "remove";
        } elseif ($data['paket_mode'] == "ntfc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($data['paket_mode'] == "0" && $price != "") {
            $onlogin = ':put (",,' . $price . ',,,noexp,' . $getlock . ',")' . $lock;
            $mode = "";
        } else {
            $onlogin = "";
        }

        $bgservice = ':local dateint do={:local montharray ( "jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec" );:local days [ :pick $d 4 6 ];:local month [ :pick $d 0 3 ];:local year [ :pick $d 7 11 ];:local monthint ([ :find $montharray $month]);:local month ($monthint + 1);:if ( [len $month] = 1) do={:local zero ("0");:return [:tonum ("$year$zero$month$days")];} else={:return [:tonum ("$year$month$days")];}}; :local timeint do={ :local hours [ :pick $t 0 2 ]; :local minutes [ :pick $t 3 5 ]; :return ($hours * 60 + $minutes) ; }; :local date [ /system clock get date ]; :local time [ /system clock get time ]; :local today [$dateint d=$date] ; :local curtime [$timeint t=$time] ; :foreach i in [ /ip hotspot user find where profile="' . $name . '" ] do={ :local comment [ /ip hotspot user get $i comment]; :local name [ /ip hotspot user get $i name]; :local gettime [:pic $comment 12 20]; :if ([:pic $comment 3] = "/" and [:pic $comment 6] = "/") do={:local expd [$dateint d=$comment] ; :local expt [$timeint t=$gettime] ; :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime)) do={ [ /ip hotspot user ' . $mode . ' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}}';





        $router = Router::where('id', $request->router)->first();
        // dd($router);
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($router->router_ip . ':' . $router->router_port_api, $router->router_username, $router->router_password)) {


            $API->comm('/ip/hotspot/user/profile/add', [
                'name' =>  $name == '' ? '' : $name,
                "rate-limit" => $data['paket_limitasi'] == '' ? '' : $data['paket_limitasi'],
                'keepalive-timeout' => '1m' == '' ? '' : '1m',
                'status-autorefresh' => '1m' == '' ? '' : '1m',
                "on-login" => "$onlogin",
                'shared-users' => $data['paket_shared'] == '' ? '' : $data['paket_shared'],
                'add-mac-cookie' => 'yes' == '' ? '' : 'yes',
                'mac-cookie-timeout' => $data['paket_masa_aktif'] == '' ? '' : $data['paket_masa_aktif'],

            ]);
            if ($data['paket_mode'] != "0") {
                if (empty($monid)) {
                    $API->comm("/system/scheduler/add", array(
                        "name" => "$name",
                        "start-time" => "$randstarttime",
                        "interval" => "$randinterval",
                        "on-event" => "$bgservice",
                        "disabled" => "no",
                        "comment" => "Monitor Profile $name",
                    ));
                } else {
                    $API->comm("/system/scheduler/set", array(
                        ".id" => "$monid",
                        "name" => "$name",
                        "start-time" => "$randstarttime",
                        "interval" => "$randinterval",
                        "on-event" => "$bgservice",
                        "disabled" => "no",
                        "comment" => "Monitor Profile $name",
                    ));
                }
            }
            // dd($data);
            Paket::create($data);
        }
        // else {
        //     $API->comm("/system/scheduler/remove", array(
        //         ".id" => "$monid"
        //     ));
        // }
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Profile Hotspot',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.paket.index')->with($notifikasi);
    }
}
