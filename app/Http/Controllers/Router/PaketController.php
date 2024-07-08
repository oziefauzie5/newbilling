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

class PaketController extends Controller
{
    public function index()
    {
        $data['tittle'] = 'PAKET INTERNET';
        $data['data_paket'] = Paket::all();
        $data['data_router'] = Router::all();
        return view('Router/paket', $data);
    }
    public function create()
    {
        // dd(Router::all());
        $data = array(
            'tittle' => 'PAKET',
            'data_router' => Router::all(),
        );
        return view('router/paket_create', $data);
    }

    public function store(Request $request)
    {
        Session::flash('paket_nama', $request->paket_nama);
        Session::flash('paket_komisi', $request->paket_komisi);
        Session::flash('paket_limitasi', $request->paket_limitasi);
        Session::flash('paket_lokal', $request->paket_lokal);
        Session::flash('paket_harga', $request->paket_harga);
        $request->validate([
            'paket_nama' => 'unique:pakets',
        ], [
            'paket_nama.unique' => 'Nama Paket tidak boleh sama',
        ]);
        // $paketreplace = str_replace(" ", "_", strtoupper($request->paket_nama));
        $data['id'] = $request->id;
        $data['paket_nama'] = $request->paket_nama;
        $data['paket_komisi'] = $request->paket_komisi;
        $data['paket_limitasi'] = $request->paket_limitasi;
        $data['paket_shared'] = $request->paket_shared;
        $data['paket_lokal'] = $request->paket_lokal;
        $data['paket_masa_aktif'] = $request->paket_masa_aktif;
        $data['paket_harga'] = $request->paket_harga;
        $data['paket_status'] = 'Enable';
        Paket::create($data);

        $router = Router::all();
        $data_paket = Paket::all();
        $API = new RouterosAPI();
        $API->debug = false;
        foreach ($router as $d) {
            foreach ($data_paket as $dp) {

                if ($API->connect($d->router_ip . ':' . $d->router_port_api, $d->router_username, $d->router_password)) {
                    $API->comm('/ip/pool/add', [
                        'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                        'ranges' =>  '10.100.192.100-10.100.207.254' == '' ? '' : '10.100.192.100-10.100.207.254',
                    ]);
                    $API->comm('/ppp/profile/add', [
                        'name' =>  $dp->paket_nama == '' ? '' : $dp->paket_nama,
                        'rate-limit' => $dp->paket_limitasi == '' ? '' : $dp->paket_limitasi,
                        'local-address' => $request->paket_lokal == '' ? '' : $request->paket_lokal,
                        'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                        'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                        'queue-type' => 'default-small' == '' ? '' : 'default-small',
                        'dns-server' => $d->router_dns == '' ? '' : $d->router_dns,
                        'disabled' => 'yes',
                        'only-one' => 'yes',
                    ]);
                }
            }
        }
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Pelanggan',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.paket.index')->with($notifikasi);
    }
    public function store_isolir(Request $request)
    {

        $cek_paket = Paket::where('paket_nama', 'APPBILL_ISOLIR')->first();
        if (!$cek_paket) {
            $data['paket_nama'] = 'APPBILL_ISOLIR';
            $data['paket_komisi'] = '0';
            $data['paket_limitasi'] = '128K/128K 0/0 0/0 0/0 8 0/0';
            $data['paket_lokal'] = '10.200.108.1';
            $data['paket_harga'] = '0';
            $data['paket_status'] = 'Enable';
            Paket::create($data);
        }


        $router = Router::all();
        $data_paket = Paket::all();
        $API = new RouterosAPI();
        $API->debug = false;
        foreach ($router as $d) {
            foreach ($data_paket as $dp) {

                if ($API->connect($d->router_ip . ':' . $d->router_port_api, $d->router_username, $d->router_password)) {
                    $API->comm('/ip/pool/add', [
                        'name' =>  $dp->paket_nama == '' ? '' : $dp->paket_nama,
                        'ranges' =>  '10.100.108.100-10.100.110.254' == '' ? '' : '10.100.108.100-10.100.110.254',
                    ]);
                    $API->comm('/ppp/profile/add', [
                        'name' =>  $dp->paket_nama  == '' ? '' : $dp->paket_nama,
                        'rate-limit' => $dp->paket_limitasi == '' ? '' : $dp->paket_limitasi,
                        'local-address' => $dp['paket_lokal'] == '' ? '' : $dp['paket_lokal'],
                        'remote-address' => $dp->paket_nama == '' ? '' : $dp->paket_nama,
                        'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                        'queue-type' => 'default-small' == '' ? '' : 'default-small',
                        'dns-server' => $d->router_dns == '' ? '' : $d->router_dns,
                    ]);
                }
            }
        }
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Paket Isolir',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.paket.index')->with($notifikasi);
    }
    public function update(Request $request, $id)
    {


        $sbiaya = SettingBiaya::first();
        $data['paket_nama'] = $request->paket_nama;
        $data['paket_komisi'] = $request->paket_komisi;
        $data['paket_limitasi'] = $request->paket_limitasi;
        $data['paket_shared'] = $request->paket_shared;
        $data['paket_masa_aktif'] = $request->paket_masa_aktif;
        $data['paket_harga'] = $request->paket_harga;
        $data['paket_status'] = 'Enable';

        $update['reg_harga'] = $request->paket_harga;
        $update['reg_ppn'] = $sbiaya->biaya_ppn / 100 * $request->paket_harga;
        Registrasi::where('reg_profile', $id)->update($update);
        Paket::where('paket_id', $id)->update($data);

        $router = Router::all();
        $data_paket = Paket::where('paket_id', $id)->get();
        $API = new RouterosAPI();
        $API->debug = false;



        foreach ($router as $d) {
            if ($API->connect($d->router_ip . ':' . $d->router_port_api, $d->router_username, $d->router_password)) {
                $cek = $API->comm('/ppp/profile/print', [
                    '?comment' => 'default by appbill ( jangan diubah )',
                ]);
                foreach ($cek as $c) {
                    $API->comm('/ppp/profile/set', [
                        '.id' =>  $c['.id'],
                        'name' =>  $request->paket_nama == '' ? '' : $request->paket_nama,
                        'rate-limit' => $request->paket_limitasi == '' ? '' : $request->paket_limitasi,
                    ]);
                }
            }
        }


        $notifikasi = array(
            'pesan' => 'Berhasil merubah paket',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.paket.index')->with($notifikasi);
    }

    public function exportPaketToMikrotik(Request $request)
    {
        $router = Router::all();
        $data_paket = Paket::all();
        $API = new RouterosAPI();
        $API->debug = false;
        foreach ($router as $d) {
            foreach ($data_paket as $dp) {

                echo $d->router_nama . $dp->paket_nama . '<br>';

                if ($API->connect($d->router_ip . ':' . $d->router_port_api, $d->router_username, $d->router_password)) {
                    $API->comm('/ip/pool/add', [
                        'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                        'ranges' =>  '10.100.192.100-10.100.207.254' == '' ? '' : '10.100.192.100-10.100.207.254',
                    ]);
                    $API->comm('/ppp/profile/add', [
                        'name' =>  $dp->paket_nama == '' ? '' : $dp->paket_nama,
                        'rate-limit' => $dp->paket_limitasi == '' ? '' : $dp->paket_limitasi,
                        'local-address' => $request->paket_lokal == '' ? '' : $request->paket_lokal,
                        'remote-address' => $request->pool == '' ? '' : $request->pool,
                        'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                        'queue-type' => 'default-small' == '' ? '' : 'default-small',
                        'dns-server' => $d->router_dns == '' ? '' : $d->router_dns,
                        'disabled' => 'yes',
                        'only-one' => 'yes',
                    ]);
                }
            }
        }
        $notifikasi = array(
            'pesan' => 'Berhasil export Paket',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.paket.index')->with($notifikasi);


        // $data_paket = Paket::all();
        // $router = Router::where("id", $request->paket_router)->first();
        // $API = new RouterosAPI();
        // $API->debug = false;
        // if ($API->connect($router->router_ip . ':' . $router->router_port_api, $router->router_username, $router->router_password)) {
        //     foreach ($data_paket as $d) {
        //         $dns = $request->dns1 . ',' . $request->dns2;
        //         $API->comm('/ppp/profile/add', [
        //             'name' =>  $d->paket_nama == '' ? '' : $d->paket_nama,
        //             'rate-limit' => $d->paket_limitasi == '' ? '' : $d->paket_limitasi,
        //             'local-address' => $request->paket_lokal == '' ? '' : $request->paket_lokal,
        //             'remote-address' => $request->pool == '' ? '' : $request->pool,
        //             'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
        //             'queue-type' => 'default-small' == '' ? '' : 'default-small',
        //             'dns-server' => $dns == '' ? '' : $dns,
        //             'only-one' => 'yes',
        //         ]);
        //     }
        //     $notifikasi = array(
        //         'pesan' => 'Berhasil export Paket',
        //         'alert' => 'success',
        //     );
        //     return redirect()->route('admin.router.paket.index')->with($notifikasi);
        // } else {
        //     $notifikasi = array(
        //         'pesan' => 'Gagal export Paket',
        //         'alert' => 'error',
        //     );
        //     return redirect()->route('admin.router.paket.index')->with($notifikasi);
        // }



        // $router = Router::where("id", $request->paket_router)->first();
        // $API = new RouterosAPI();
        // $API->debug = false;
        // $dns = $request->dns1 . ',' . $request->dns2;
        // if ($API->connect($router->router_ip . ':' . $router->router_port_api, $router->router_username, $router->router_password)) {
        //     $API->comm('/ppp/profile/add', [
        //         'name' =>  $data_paket->paket_nama == '' ? '' : $data_paket->paket_nama,
        //         'rate-limit' => $data_paket['paket_limitasi'] == '' ? '' : $data_paket['paket_limitasi'],
        //         'local-address' => $request->paket_lokal == '' ? '' : $request->paket_lokal,
        //         'remote-address' => $request->pool == '' ? '' : $request->pool,
        //         'comment' => 'appbill' == '' ? '' : 'appbill',
        //         'queue-type' => 'default-small' == '' ? '' : 'default-small',
        //         'dns-server' => $dns == '' ? '' : $dns,
        //         'only-one' => 'yes',
        //     ]);
        //     $notifikasi = array(
        //         'pesan' => 'Berhasil export Paket',
        //         'alert' => 'success',
        //     );
        //     return redirect()->route('admin.router.paket.index')->with($notifikasi);
        // } else {
        //     $notifikasi = array(
        //         'pesan' => 'Gagal export Paket',
        //         'alert' => 'error',
        //     );
        //     return redirect()->route('admin.router.paket.index')->with($notifikasi);
        // }
    }

    public function getRouter($id)
    {
        // return response()->json($id);
        $data_router = Router::where("id", $id)->get();

        $ip = $data_router[0]->router_ip . ':' . $data_router[0]->router_port_api;
        $user = $data_router[0]->router_username;
        $pass = $data_router[0]->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $getallpoll = $API->comm("/ip/pool/print");
            $TotalReg = count($getallpoll);
            $API->disconnect();
            return response()->json($getallpoll);
        }
        // $data_router = Router::where("id", $request->id)->get();
    }
}
