<?php

namespace App\Http\Controllers\Router;

use App\Exports\Export\ExportUsername;
use App\Http\Controllers\Controller;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Teknisi\Data_pop;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RouterController extends Controller
{
    public function index()
    {
        $data['router'] = Router::join('data_pops', 'data_pops.pop_id', '=', 'routers.router_id_pop')->get();
        $data['data_pop'] = Data_pop::where('pop_status', 'Enable')->get();
        return view('Router/index', $data);
    }
    public function store(Request $request)
    {

        $data['router_nama'] = $request->router_nama;
        $data['router_id_pop'] = $request->router_id_pop;
        $data['router_ip'] = $request->router_ip;
        $data['router_dns'] = $request->router_dns;
        $data['router_port_api'] = $request->router_port_api;
        $data['router_port_remote'] = $request->router_port_remote;
        $data['router_username'] = $request->router_username;
        $data['router_password'] = $request->router_password;
        $data['router_status'] = 'Enable';

        Router::create($data);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan router',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.index')->with($notifikasi);
    }
    public function edit(Request $request, $id)
    {

        $data['router_nama'] = $request->router_nama;
        $data['router_id_pop'] = $request->router_id_pop;
        $data['router_ip'] = $request->router_ip;
        $data['router_dns'] = $request->router_dns;
        $data['router_port_api'] = $request->router_port_api;
        $data['router_port_remote'] = $request->router_port_remote;
        $data['router_username'] = $request->router_username;
        $data['router_password'] = $request->router_password;

        Router::whereId($id)->update($data);

        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $cek = $API->comm('/ppp/profile/print', [
                '?comment' => 'default by appbill ( jangan diubah )',
            ]);
            foreach ($cek as $c) {
                $API->comm('/ppp/profile/set', [
                    '.id' =>  $c['.id'],
                    'dns-server' => $request->router_dns == '' ? '' : $request->router_dns,
                ]);
                echo $c['.id'];
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal edit router',
                'alert' => 'error',
            );
            return redirect()->route('admin.router.index')->with($notifikasi);
        }
        $notifikasi = array(
            'pesan' => 'Berhasil edit router',
            'alert' => 'success',
        );
        return redirect()->route('admin.router.index')->with($notifikasi);
    }
    public function delete_router(Request $request, $id)
    {
        $data = Router::find($id);
        if ($data) {
            $data->delete();
        }
        $notifikasi = array(
            'alert' => 'success',
            'mik' => 'Berhasil menghapus Router',
        );
        return redirect()->route('admin.router.index')->with($notifikasi);
    }
    public function cekRouter($id)
    {

        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $resource = $API->comm('/system/resource/print');
            $data['resource'] = $resource[0]['cpu-load'];
            $data['architecture'] = $resource[0]['architecture-name'];
            $data['cpu'] = $resource[0]['cpu'];
            $data['version'] = $resource[0]['version'];
            $cek = $API->comm('/ppp/profile/print', [
                '?comment' => 'default by appbill ( jangan diubah )',
            ]);
            $data['cek'] = $cek[0]['dns-server'];
        } else {
            $data['resource'] = 'error';
            $data['router_nama'] = $router->router_nama;
        }

        return response()->json($data);
    }

    public function getPppoe($id)
    {
        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        $data['id'] = $id;
        if ($API->connect($ip, $user, $pass)) {
            $data['getuseractive'] = $API->comm('/ppp/active/print');
            //     foreach ($data['getuseractive'] as $d) {


            //         echo ' <table>
            //     <tr>
            //         <td>' . $d['name'] . '</td>
            //     </tr>
            //     </tr>
            // </table>';
            //     }
            return view('Router/pppoe', $data);
        } else {
            dd('tidak konek');
            return redirect()->route('admin.router.index');
        }
    }
    public function getHotspot($id)
    {
        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        $data['id'] = $id;
        if ($API->connect($ip, $user, $pass)) {
            $data['getuseractive'] = $API->comm('/ip/hotspot/active/print');
            return view('Router/hotspot', $data);
        } else {
            dd('tidak konek');
            return redirect()->route('admin.router.index');
        }
    }
    public function router_remote($id, $ipremote)
    {
        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        $data['id'] = $id;
        if ($API->connect($ip, $user, $pass)) {
            $data['getNat'] = $API->comm('/ip/firewal/nat/print', [
                '?comment' => 'REMOTE_ONT',
            ]);
            if ($data['getNat']) {
                $API->comm('/ip/firewal/nat/set', [
                    '.id' => $data['getNat'][0]['.id'],
                    'to-addresses' => $ipremote,
                ]);
            } else {
                $API->comm('/ip/firewal/nat/add', [
                    'chain' => 'dstnat',
                    'protocol' => 'tcp',
                    'dst-address' => $router->router_ip,
                    'dst-port' => '8889',
                    'action' => 'dst-nat',
                    'to-ports' => '80',
                    'to-addresses' => $ipremote,
                    'log' => 'no',
                    'comment' => 'REMOTE_ONT',
                ]);
            }

            $data['getuseractive'] = $API->comm('/ppp/active/print');
            return redirect()->to('http://' . $router->router_ip . ':' . $router->router_port_remote . '/');
        } else {
            dd('tidak konek');
            return redirect()->route('admin.router.index');
        }
    }
    public function kick_hotspot($id, $idmik)
    {
        $router = Router::whereId($id)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        $data['id'] = $id;
        if ($API->connect($ip, $user, $pass)) {
            $API->comm('/ip/hotspot/active/remove', [
                '.id' =>  $idmik,
            ]);
            $data['getuseractive'] = $API->comm('/ip/hotspot/active/print');
            return view('Router/hotspot', $data);
        } else {
            dd('tidak konek');
            return redirect()->route('admin.router.hotspot');
        }
    }
}
