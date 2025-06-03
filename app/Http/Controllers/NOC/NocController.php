<?php

namespace App\Http\Controllers\NOC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Barang\SubBarang;
use App\Models\Gudang\Data_Barang;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Teknisi\Teknisi;
use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

    public function detail_pelanggan($id)
    {
        $data = "cek";
        return view('noc/detail_pelanggan', $data);
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
    public function upload(Request $request, $id)
    {
        $noc_id = Auth::user()->id;
        $admin = Auth::user()->name;
        if ($photo = $request->file('file')) {
            $photo = $request->file('file');
            $filename = str_replace(" ", "-", $admin) . '-' . str_replace(" ", "-",  $request->pelanggan) . '-' . $photo->getClientOriginalName();
            $path = 'photo-rumah/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            Registrasi::where('reg_progres', '2')->where('reg_idpel', $id)->update(['reg_img' => $filename]);
            $notifikasi = array(
                'pesan' => 'Upload Berhasil',
                'alert' => 'success',
            );
            return redirect()->route('admin.noc.index')->with($notifikasi);
        } else {

            $notifikasi = array(
                'pesan' => 'Upload gagal!',
                'alert' => 'error',
            );
            return redirect()->route('admin.noc.index')->with($notifikasi);
        }
    }

    #EDIT DATA PELANGGAN
    public function status_inet($id)
    {

          $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
            ->join('data__odps', 'data__odps.id', '=', 'ftth_instalasis.data__odp_id')
            ->join('data__odcs', 'data__odcs.id', '=', 'data__odps.data__odc_id')
            ->join('data__olts', 'data__olts.id', '=', 'data__odcs.data__olt_id')
            ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
             ->where('registrasis.corporate_id',Session::get('corp_id'))
            ->where('registrasis.reg_idpel', $id)
            ->select([
                'registrasis.reg_username',
                'registrasis.reg_layanan',
                'routers.router_ip',
                'routers.router_port_api',
                'routers.router_username',
                'routers.router_password',
                'routers.router_dns',
            ])->first();
        if ($data_pelanggan->reg_layanan == 'PPP') {
            $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
            $user = $data_pelanggan->router_username;
            $pass = $data_pelanggan->router_password;
            $API = new RouterosAPI();
            $API->debug = false;
            if ($API->connect($ip, $user, $pass)) {
                $cek = $API->comm('/ppp/active/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $status_pelanggan = $cek_secret[0]['disabled'];
                } else {
                    $status_pelanggan = 'null';
                }
                // dd($status_pelanggan );
                if ($cek) {
                    $data['uptime'] = $cek['0']['uptime'];
                    $data['status'] = "CONNECTED";
                    $data['address'] = $cek['0']['address'];
                    $data['status_secret'] = $status_pelanggan;
                } else {
                    $data['address'] = '-';
                    $data['status']  = "DISCONNECTED";
                    $data['uptime'] = "-";
                    $data['status_secret'] = $status_pelanggan;
                }
                return $data;
            } else {
                $data['address'] = 'Router Disconnected';
                $data['status']  = "TIDAK TERSAMBUNG KE SERVER";
                $data['uptime'] = "-";
                $data['status_secret'] = 'Router Disconnected';
                return $data;
            }
        } elseif ($data_pelanggan->reg_layanan == 'HOTSPOT') {
            $router = Router::whereId($data_pelanggan->reg_router)->first();
            $ip =   $router->router_ip . ':' . $router->router_port_api;
            $user = $router->router_username;
            $pass = $router->router_password;
            $API = new RouterosAPI();
            $API->debug = false;

            // dd($data_pelanggan->reg_username);

            if ($API->connect($ip, $user, $pass)) {
                $cek = $API->comm('/ip/hotspot/active/print', [
                    '?user' => $data_pelanggan->reg_username,
                ]);
                $cek_secret = $API->comm('/ip/hotspot/user/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $status_pelanggan = $cek_secret[0]['disabled'];
                } else {
                    $status_pelanggan = 'null';
                }
                if ($cek) {
                    $data['uptime'] = $cek['0']['uptime'];
                    $data['status'] = "CONNECTED";
                    $data['address'] = $cek['0']['address'];
                    $data['status_secret'] = $status_pelanggan;
                } else {
                    $data['address'] = '-';
                    $data['status']  = "DISCONNECTED";
                    $data['uptime'] = "-";
                    $data['status_secret'] = $status_pelanggan;
                }
                // dd($data);
                return $data;
                // return view('Router/pppoe', $data);
            } else {
                dd('Router Disconnected');
            }
        }
    }
    #EDIT DATA PELANGGAN
    public function isolir_manual($id)
    {

        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('reg_idpel', $id)->first();
        // $router = Router::whereId($data_pelanggan->reg_router)->first();
        $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
        $user = $data_pelanggan->router_username;
        $pass = $data_pelanggan->router_password;
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
                    'comment' => 'ISOLIR MANUAL',
                    'disabled' => 'yes',
                ]);
                // 'profile' => 'APPBILL_ISOLIR',
                // dd($cek_secret);
                Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                    'inv_status' => 'ISOLIR',
                ]);
                Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                    'reg_status' => 'ISOLIR',
                ]);
                $status = (new GlobalController)->whatsapp_status();

                if ($status->wa_status == 'Enable') {
                    $pesan_group['status'] = '0';
                } else {
                    $pesan_group['status'] = '10';
                }


                $pesan_group['ket'] = 'isolir manual';
                $pesan_group['status'] = '0';
                $pesan_group['target'] = $data_pelanggan->input_hp;
                $pesan_group['nama'] = $data_pelanggan->input_nama;
                $pesan_group['pesan'] = '
Pelanggan yang terhormat,
Kami informasikan bahwa layanan internet anda saat ini sedang di *ISOLIR* oleh sistem secara otomatis❗, kami mohon maaf atas ketidaknyamanannya
Agar dapat digunakan kembali dimohon untuk melakukan pembayaran tagihan sebagai berikut :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->input_nama . '
Invoice : 013524
Jatuh Tempo : ' . $data_pelanggan->reg_tgl_jatuh_tempo . '
Total tagihan :Rp. *' . number_format($data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama) . '*

Untuk melihat detail layanan dan pembayaran tagihan bisa melalui client area *'.env('LINK_APK').'*
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*'.Session::get('app_brand').'*


';
                Pesan::create($pesan_group);
                $cek_status = $API->comm('/ppp/active/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_status) {
                    $API->comm('/ppp/active/remove', [
                        '.id' =>  $cek_status['0']['.id'],
                    ]);
                    $notifikasi = array(
                        'pesan' => 'ISOLIR Pelanggan berhasil',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $API->comm('/ppp/secret/add', [
                    'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                    'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                    'service' => 'pppoe',
                    'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                    'comment' =>  'ISOLIR MANUAL' == '' ? '' : 'ISOLIR MANUAL',
                    'disabled' => 'yes',
                ]);
                Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                    'inv_status' => 'ISOLIR',
                ]);
                Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                    'reg_status' => 'ISOLIR',
                ]);
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $notifikasi = array(
                        'pesan' => 'ISOLIR Pelanggan berhasil',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Secret tidak ditemukan pada Router',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Maaf..!! Router Disconnected',
                'alert' => 'error',
            );
            return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
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
                    'comment' => 'BUKA ISOLIR MANUAL',
                    'disabled' => 'no',
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
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $API->comm('/ppp/secret/add', [
                    'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                    'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                    'service' => 'pppoe',
                    'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                    'comment' => 'BUKA ISOLIR MANUAL',
                    'disabled' => 'no',

                ]);
                $notifikasi = array(
                    'pesan' => 'Berhasil buka isolir manual ( Secret ditambahkan pada Router )',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Maaf..!! Router Disconnected',
                'alert' => 'error',
            );
            return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        }
    }
    public function status_secret(Request $request, $id)
    {
        $admin = Auth::user()->name;
        $data_pelanggan = Registrasi::join('routers', 'routers.id', '=', 'registrasis.reg_router')->where('reg_idpel', $id)->first();
        $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
        $user = $data_pelanggan->router_username;
        $pass = $data_pelanggan->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($request->query('stat') == 'false') {
            $proses = 'yes';
            $comment = 'Disable';
        } else {
            $comment = 'Enable';
            $proses = 'no';
        }
        if ($data_pelanggan->reg_layanan == 'PPP') {
            if ($API->connect($ip, $user, $pass)) {
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $API->comm('/ppp/secret/set', [
                        '.id' => $cek_secret[0]['.id'],
                        'comment' => $comment . ' By-' . $admin,
                        'disabled' => $proses,
                    ]);
                    $cek_status = $API->comm('/ppp/active/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_status) {
                        if ($request->query('stat') == 'false') {
                            $API->comm('/ppp/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                        $notifikasi = array(
                            'pesan' => 'Disable Pelanggan berhasil',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Disable Pelanggan berhasil. Pelanggan Sedang Disconnected',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    }
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Maaf..!! Router Disconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            if ($API->connect($ip, $user, $pass)) {
                $cek_secret = $API->comm('/ip/hotspot/user/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $API->comm('/ip/hotspot/user/set', [
                        '.id' => $cek_secret[0]['.id'],
                        'comment' => $comment . ' By-' . $admin,
                        'disabled' => $proses,
                    ]);
                    $cek_status = $API->comm('/ip/hotspot/active/print', [
                        '?user' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_status) {
                        if ($request->query('stat') == 'false') {
                            $API->comm('/ip/hotspot/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                        $notifikasi = array(
                            'pesan' => 'Disable Pelanggan berhasil',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Disable Pelanggan berhasil. Pelanggan Sedang Disconnected',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    }
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Maaf..!! Router Disconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        }
    }
    public function kick(Request $request, $id)
    {

        $data_pelanggan = Registrasi::join('routers', 'routers.id', '=', 'registrasis.reg_router')->where('reg_idpel', $id)->first();
        $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
        $user = $data_pelanggan->router_username;
        $pass = $data_pelanggan->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($data_pelanggan->reg_layanan == 'PPP') {
            if ($API->connect($ip, $user, $pass)) {

                $cek_status = $API->comm('/ppp/active/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                // dd($cek_status);
                if ($cek_status) {
                    $API->comm('/ppp/active/remove', [
                        '.id' =>  $cek_status['0']['.id'],
                    ]);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Pelanggan Sedang Disconnected',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Maaf..!! Router Disconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            if ($API->connect($ip, $user, $pass)) {
                $cek_status = $API->comm('/ip/hotspot/active/print', [
                    '?user' => $data_pelanggan->reg_username,
                ]);
                if ($cek_status) {
                    $API->comm('/ip/hotspot/active/remove', [
                        '.id' =>  $cek_status['0']['.id'],
                    ]);
                    $notifikasi = array(
                        'pesan' => 'Kick Pelanggan berhasil.',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Kick Pelanggan berhasil. Pelanggan sudah Disconnected',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Maaf..!! Router Disconnected',
                    'alert' => 'error',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        }
    }
    public function pengecekan_barang(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Data_Barang::orderBy('data__barangs.created_at', 'DESC')->where('barang_dicek', '>', 0)
            ->where(function ($query) use ($data) {
                $query->where('barang_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('barang_kategori', 'like', '%' . $data['q'] . '%');
            });
        $data['data_barang'] = $query->paginate(10);
        $data['count_antrian'] = Data_Barang::where('barang_dicek', '>', 0)->count();
        // $data['sub_barang'] = SubBarang::where('subbarang_status', '>=', 4)->where('subbarang_status', '<=', 5)->get();
        // $data['count_antrian'] = SubBarang::where('subbarang_status', '>=', 4)->where('subbarang_status', '<=', 5)->count();
        return view('noc/pengecekan-barang', $data);
    }
    public function update_status_barang(Request $request, $id)
    {

        $user = (new GlobalController)->user_admin()['user_id'];

        if ($request->barang_status == 'Normal') {
            $barang_dicek = 0;
            $barang_rusak = 0;
        } else {
            $barang_dicek = 0;
            $barang_rusak = 1;
        }

         if($request->barang_ket == 'Pengambilan Perangkat'){
            $sub['barang_dicek'] = $barang_dicek;
            $sub['barang_rusak'] = $barang_rusak;
            $sub['barang_status'] = $request->barang_status;
            $sub['barang_ket'] = $request->barang_ket;
            $sub['barang_pengecek'] = $user;
            Data_Barang::where('barang_id', $id)->update($sub);
            $notifikasi = array(
                'pesan' => 'Berhasil Update Status Barang',
                'alert' => 'success',
            );
            return redirect()->route('admin.noc.pengecekan_barang')->with($notifikasi);
            } elseif($request->barang_ket == 'Pergantian Perangkat'){

            } else{

                if ($request->barang_mac) {
           
            $cek_barang = Data_Barang::where('barang_mac', $request->barang_mac)->first();
            $cek_sn = Data_Barang::where('barang_sn', $request->barang_sn)->first();
            // dd($cek_sn);
            if ($cek_barang) {
                $notifikasi = array(
                    'pesan' => 'Gagal, Mac Address sudah terdaftar',
                    'alert' => 'error',
                );
                return redirect()->route('admin.noc.pengecekan_barang')->with($notifikasi);
            } else {
                if ($cek_sn) {
                    $notifikasi = array(
                        'pesan' => 'Gagal, Serial Number sudah terdaftar',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.noc.pengecekan_barang')->with($notifikasi);
                } else {
                    $sub['barang_mac'] = $request->barang_mac;
                    $sub['barang_mac_olt'] = $request->barang_mac_olt;
                    $sub['barang_sn'] = $request->barang_sn;
                    $sub['barang_status'] = $request->barang_status;
                    $sub['barang_ket'] = $request->barang_ket;
                    $sub['barang_pengecek'] = $user;
                    $sub['barang_dicek'] = $barang_dicek;
                    $sub['barang_rusak'] = $barang_rusak;


                    Data_Barang::where('barang_id', $id)->update($sub);
                    $notifikasi = array(
                        'pesan' => 'Berhasil Update Status Barang',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.noc.pengecekan_barang')->with($notifikasi);
                }
            }
        } else {
            $sub['barang_dicek'] = $barang_dicek;
            $sub['barang_rusak'] = $barang_rusak;
            $sub['barang_status'] = $request->barang_status;
            $sub['barang_ket'] = $request->barang_ket;
            $sub['barang_pengecek'] = $user;


            Data_Barang::where('barang_id', $id)->update($sub);
            $notifikasi = array(
                'pesan' => 'Berhasil Update Status Barang',
                'alert' => 'success',
            );
            return redirect()->route('admin.noc.pengecekan_barang')->with($notifikasi);
        }

            }

        
    }
}
