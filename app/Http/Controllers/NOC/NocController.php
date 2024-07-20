<?php

namespace App\Http\Controllers\NOC;

use App\Http\Controllers\Controller;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Teknisi\Teknisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NocController extends Controller
{
    public function index(Request $request)
    {
        $month = Carbon::now()->addMonth(-0)->format('m');
        $bulan_lalu = Carbon::now()->addMonth(-1)->format('m');

        $data['router'] = $request->query('router');
        $data['data'] = $request->query('data');

        if ($data['router']) {
            $r = Router::where('id', $data['router'])->first();
            $data['r_nama'] = $r->router_nama;
        }

        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('registrasis.reg_progres', '2')
            ->orderBy('tgl', 'DESC');


        if ($data['router'])
            $query->where('routers.id', '=', $data['router']);
        if ($data['data'] == "PPP")
            $query->where('registrasis.reg_layanan', '=', "PPP");
        elseif ($data['data'] == "DHCP")
            $query->where('registrasis.reg_layanan', '=', "DHCP");
        elseif ($data['data'] == "HOTSPOT")
            $query->where('registrasis.reg_layanan', '=', "HOTSPOT");
        elseif ($data['data'] == "USER BARU")
            $query->whereMonth('reg_tgl_pasang', '=', $month);
        elseif ($data['data'] == "USER BULAN LALU")
            $query->whereMonth('reg_tgl_pasang', '=', $bulan_lalu);


        $data['data_registrasi'] = $query->get();

        $data['count_antrian'] = Registrasi::where('reg_progres', '2')->count();
        $data['count_selesai'] = Registrasi::where('reg_progres', '3')->count();

        $data['get_router'] = Router::get();

        return view('noc/index', $data);
    }

    public function pengecekan($id)
    {
        $data_pelanggan = Registrasi::where('reg_idpel', $id)->first();
        $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;
        $data['id'] = $id;

        // dd($data_pelanggan->reg_username);

        if ($API->connect($ip, $user, $pass)) {
            $cek = $API->comm('/ppp/active/print', [
                '?name' => $data_pelanggan->reg_username,
            ]);
            if ($cek = $cek) {
                foreach ($cek as $c) {
                    $data['getNat'] = $API->comm('/ip/firewal/nat/print', [
                        '?comment' => 'REMOTE_ONT',
                    ]);
                    if ($data['getNat']) {
                        $API->comm('/ip/firewal/nat/set', [
                            '.id' => $data['getNat'][0]['.id'],
                            'to-addresses' => $c['address'],
                        ]);
                    } else {
                        $API->comm('/ip/firewal/nat/add', [
                            'chain' => 'dstnat',
                            'protocol' => 'tcp',
                            'dst-address' => $router->router_ip,
                            'dst-port' => '8889',
                            'action' => 'dst-nat',
                            'to-ports' => '80',
                            'to-addresses' => $c['address'],
                            'log' => 'no',
                            'comment' => 'REMOTE_ONT',
                        ]);
                    }
                    return redirect()->to('http://' . $router->router_ip . ':' . $router->router_port_remote . '/');
                    // dd($c['address']);
                }
            } else {
                dd('Pelanggan tidak aktif');
            }
            // return view('Router/pppoe', $data);
        } else {
            dd('Router Disconnected');
        }
    }
    public function pengecekan_put($id)
    {
        $noc_id = Auth::user()->id;
        Registrasi::where('reg_progres', '2')->where('reg_idpel', $id)->update(['reg_progres' => '3']);
        Teknisi::where('teknisi_idpel', $id)->where('teknisi_job', 'PSB')->where('teknisi_psb', '>', '0')->update(['teknisi_noc_userid' => $noc_id]);

        $notifikasi = array(
            'pesan' => 'Pengecekan Selesai',
            'alert' => 'success',
        );
        return redirect()->route('admin.noc.index')->with($notifikasi);
    }

    #EDIT DATA PELANGGAN
    public function status_inet($id)
    {


        $data_pelanggan = Registrasi::where('reg_idpel', $id)->first();
        $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        // dd($data_pelanggan->reg_username);

        if ($API->connect($ip, $user, $pass)) {
            $cek = $API->comm('/ppp/active/print', [
                '?name' => $data_pelanggan->reg_username,
            ]);
            if ($cek) {
                $data['uptime'] = $cek['0']['uptime'];
                $data['status'] = "CONNECTED";
                $data['address'] = $cek['0']['address'];
            } else {
                $data['address'] = '-';
                $data['status']  = "DISCONNECTED";
                $data['uptime'] = "-";
            }
            return $data;
            // dd($data);
            // return view('Router/pppoe', $data);
        } else {
            dd('Router Disconnected');
        }
    }
    #EDIT DATA PELANGGAN
    public function isolir_manual($id)
    {

        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('reg_idpel', $id)->first();
        $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        // dd($data_pelanggan->reg_username);

        if ($API->connect($ip, $user, $pass)) {
            $cek_secret = $API->comm('/ppp/secret/print', [
                '?name' => $data_pelanggan->reg_username,
            ]);
            if ($cek_secret) {
                $API->comm('/ppp/secret/set', [
                    '.id' => $cek_secret[0]['.id'],
                    'profile' => 'APPBILL_ISOLIR',
                ]);
                $cek_status = $API->comm('/ppp/active/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_status) {
                    $API->comm('/ppp/active/remove', [
                        '.id' =>  $cek_status['0']['.id'],
                    ]);

                    $pesan_group['ket'] = 'aktivasi psb';
                    $pesan_group['status'] = '0';
                    $pesan_group['target'] = $data_pelanggan->input_hp;
                    $pesan_group['pesan'] = '
Pelanggan yang terhormat,
Kami informasikan bahwa layanan internet anda saat ini sedang di *ISOLIR* oleh sistem secara otomatis❗, kami mohon maaf atas ketidaknyamanannya
Agar dapat digunakan kembali dimohon untuk melakukan pembayaran tagihan sebagai berikut :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->input_nama . '
Invoice : 013524
Jatuh Tempo : ' . $data_pelanggan->reg_tgl_jatuh_tempo . '
Total tagihan :Rp. *' . number_format($data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama) . '*

--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*


';
                    Pesan::create($pesan_group);

                    $notifikasi = array(
                        'pesan' => 'ISOLIR Pelanggan berhasil',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Secret tidak ditemukan pada Router',
                    'alert' => 'error',
                );
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Maaf..!! Router Disconnected',
                'alert' => 'error',
            );
            return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
        }
    }
    #EDIT DATA PELANGGAN
    public function buka_isolir_manual($id)
    {

        $data_pelanggan = Registrasi::join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')->where('reg_idpel', $id)->first();
        $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        // dd($data_pelanggan->reg_username);

        if ($API->connect($ip, $user, $pass)) {
            $cek_secret = $API->comm('/ppp/secret/print', [
                '?name' => $data_pelanggan->reg_username,
            ]);
            if ($cek_secret) {
                $API->comm('/ppp/secret/set', [
                    '.id' => $cek_secret[0]['.id'],
                    'profile' => $data_pelanggan->paket_nama,
                ]);
                $cek_status = $API->comm('/ppp/active/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_status) {
                    $API->comm('/ppp/active/remove', [
                        '.id' =>  $cek_status['0']['.id'],
                    ]);
                    $notifikasi = array(
                        'pesan' => 'Buka ISOLIR Pelanggan berhasil',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $API->comm('/ppp/secret/add', [
                    'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                    'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                    'service' => 'pppoe',
                    'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                    'comment' =>  $data_pelanggan->reg_tgl_jatuh_tempo == '' ? '' : $data_pelanggan->reg_tgl_jatuh_tempo,
                    'disabled' => 'no',
                ]);
                $notifikasi = array(
                    'pesan' => 'Berhasil buka isolir manual ( Secret ditambahkan pada Router )',
                    'alert' => 'error',
                );
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Maaf..!! Router Disconnected',
                'alert' => 'error',
            );
            return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
        }
    }
}
