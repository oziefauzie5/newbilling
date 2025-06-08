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
        $data['data_paket'] = Paket::where('corporate_id',Session::get('corp_id'))->with('paket_router')->get();
        $data['data_router'] = Router::where('corporate_id',Session::get('corp_id'))->get();
        return view('Router/paket', $data);
    }
    public function paket_harga()
    {
        // dd('test');
        $data['data_paket'] = Paket::where('corporate_id',Session::get('corp_id'))->with('paket_router')->get();
        $data['data_router'] = Router::where('corporate_id',Session::get('corp_id'))->get();
        return view('Router/paket_harga', $data);
    }
    public function store(Request $request)
    {
        Session::flash('paket_nama', $request->paket_nama);
        Session::flash('paket_limitasi', $request->paket_limitasi);
        Session::flash('paket_lokal', $request->paket_lokal);
        Session::flash('router_id', $request->router_id);
        Session::flash('paket_harga', $request->paket_harga);
        Session::flash('paket_komisi', $request->paket_komisi);
        Session::flash('paket_layanan', $request->paket_layanan);
        $request->validate([
            'paket_nama' => 'unique:pakets',
        ], [
            'paket_nama.unique' => 'Nama Paket sudah digunakan',
        ]);
        $data['corporate_id'] = Session::get('corp_id');
        $data['router_id'] = $request->router_id;
        $data['paket_nama'] = $request->paket_nama;
        $data['paket_limitasi'] = $request->paket_limitasi;
        $data['paket_shared'] = $request->paket_shared;
        $data['paket_lokal'] = $request->paket_lokal;
        $data['paket_masa_aktif'] = $request->paket_masa_aktif;
        $data['paket_harga'] = $request->paket_harga;
        $data['paket_komisi'] = 0;
        $data['paket_layanan'] = 'PPP';
        $data['paket_status'] = 'Enable';

        
        $router = Router::where('corporate_id',Session::get('corp_id'))->where('id', $request->router_id)->first();
        $API = new RouterosAPI();
        $API->debug = false;
        
        
        if ($API->connect($router->router_ip . ':' . $router->router_port_api, $router->router_username, $router->router_password)) {
            $API->comm('/ip/pool/add', [
                'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                'ranges' =>  '10.100.192.100-10.100.207.254' == '' ? '' : '10.100.192.100-10.100.207.254',
            ]);
            $API->comm('/ppp/profile/add', [
                'name' =>  $request->paket_nama == '' ? '' : $request->paket_nama,
                'rate-limit' => $request->paket_nama == '' ? '' : $request->paket_nama,
                'local-address' => $request->paket_lokal == '' ? '' : $request->paket_lokal,
                'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                'comment' => 'default by appbill' == '' ? '' : 'default by appbill',
                'queue-type' => 'default-small' == '' ? '' : 'default-small',
                'dns-server' => $router->router_dns == '' ? '' : $router->router_dns,
                'only-one' => 'yes',
            ]);
            Paket::create($data);
            $notifikasi = array(
                'pesan' => 'Berhasil menambhkan paket',
                'alert' => 'success',
            );
            return redirect()->route('admin.router.noc.index')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal menambhkan paket',
                'alert' => 'error',
            );
            return redirect()->route('admin.router.noc.index')->with($notifikasi);
        }
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
                        'comment' => 'default by appbill' == '' ? '' : 'default by appbill',
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
        return redirect()->route('admin.router.noc.index')->with($notifikasi);
    }
    public function update(Request $request, $id)
    {

        if ($request->paket_layanan == 'PPP') {
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
                        '?comment' => 'default by appbill',
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
            return redirect()->route('admin.router.noc.index')->with($notifikasi);
        } elseif ($request->paket_layanan == 'HOTSPOT') {
            dd('belum bisa update paket layanan hotspot');
        } elseif ($request->paket_layanan == 'VOUCHER') {
            $data = (new PaketVoucherController)->update_paket_voucher($request, $id);
            if ($data == 'success') {
                $notifikasi = array(
                    'pesan' => 'Berhasil merubah paket',
                    'alert' => 'success',
                );
                return redirect()->route('admin.router.noc.index')->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Rubah paket tidak berhasil, Router Discconnect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.router.noc.index')->with($notifikasi);
            }
        }
    }

    public function update_harga_paket(Request $request, $id)
    {
            $sbiaya = SettingBiaya::first();
            $data['paket_harga'] = $request->paket_harga;
            $data['paket_status'] = 'Enable';

            $update['reg_harga'] = $request->paket_harga;
            $update['reg_ppn'] = $sbiaya->biaya_ppn / 100 * $request->paket_harga;
            Registrasi::where('reg_profile', $id)->update($update);
            Paket::where('paket_id', $id)->update($data);

             $notifikasi = array(
                    'pesan' => 'Berhasil merubah paket',
                    'alert' => 'success',
                );
        return redirect()->route('admin.router.paket_harga')->with($notifikasi);
    }

    public function exportPaketToMikrotik(Request $request)
    {
        $router = Router::whereId($request->paket_router)->first();

        $API = new RouterosAPI();
        $API->debug = false;
        if ($request->layanan == 'PPP') {
            $data_paket = Paket::where('paket_layanan', 'PPP')->get();
        } else {
            $data_paket = Paket::where('paket_layanan', 'HOTSPOT')->get();
        }
        foreach ($data_paket as $dp) {
            if ($API->connect($router->router_ip . ':' . $router->router_port_api, $router->router_username, $router->router_password)) {
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
                    'dns-server' => $router->router_dns == '' ? '' : $router->router_dns,
                    'only-one' => 'yes',
                ]);
            }
        }
        $notifikasi = array(
            'pesan' => 'Berhasil export Paket',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.noc.index')->with($notifikasi);
    }

    public function getRouter($id)
    {
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
    }
}
