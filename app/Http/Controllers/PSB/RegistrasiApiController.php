<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Barang\SubBarang;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use Illuminate\Http\Request;

class RegistrasiApiController extends Controller
{
    public function registrasi_api_sementara(Request $request, $id)
    {
        $regist = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.id', $id)->first();
        $router = Router::whereId($regist->reg_router)->first();
        $tgl_aktif = date('d/m/Y', strtotime($regist->created_at));


        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $API->comm('/ppp/secret/add', [
                'name' => $regist->reg_username == '' ? '' : $regist->reg_username,
                'password' => $regist->reg_password  == '' ? '' : $regist->reg_password,
                'service' => 'pppoe',
                'profile' => $regist->paket_nama  == '' ? 'default' : $regist->paket_nama,
                'comment' => $regist->reg_jenis_tagihan == '' ? '' : $regist->reg_jenis_tagihan,
                'disabled' => 'yes',
            ]);

            $notifikasi = array(
                'pesan' => 'Berhasil menambahkan pelanggan',
                'alert' => 'success',
            );
            return redirect()->route('admin.psb.index')->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal menambahkan pelanggan',
                'alert' => 'error',
            );
            return redirect()->route('admin.reg.index')->with($notifikasi);
        }
    }
    // public function registrasi_api(Request $request, $id)
    // {
    //     $regist = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
    //         ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
    //         ->where('input_data.id', $id)->first();
    //     $router = Router::whereId($regist->reg_router)->first();
    //     $tgl_aktif = date('d/m/Y', strtotime($regist->created_at));
    //     $ip =   $router->router_ip . ':' . $router->router_port_api;
    //     $user = $router->router_username;
    //     $pass = $router->router_password;
    //     $API = new RouterosAPI();
    //     $API->debug = false;

    //     if ($API->connect($ip, $user, $pass)) {
    //         $API->comm('/ppp/secret/add', [
    //             'name' => $regist->reg_username == '' ? '' : $regist->reg_username,
    //             'password' => $regist->reg_password  == '' ? '' : $regist->reg_password,
    //             'service' => 'pppoe',
    //             'profile' => $regist->paket_nama  == '' ? 'default' : $regist->paket_nama,
    //             'comment' => $regist->input_nama . '|' . $regist->reg_jenis_tagihan . '|' . $tgl_aktif . '|' . $regist->reg_mac . '|' . $regist->reg_sn == '' ? '' : $regist->input_nama . '|' . $regist->reg_jenis_tagihan . '|' . $tgl_aktif . '|' . $regist->reg_mac . '|' . $regist->reg_sn,
    //             'disabled' => 'yes',
    //         ]);

    //         $notifikasi = array(
    //             'pesan' => 'Berhasil menambahkan pelanggan',
    //             'alert' => 'success',
    //         );
    //         return redirect()->route('admin.psb.index')->with($notifikasi);
    //     } else {
    //         $notifikasi = array(
    //             'pesan' => 'Gagal menambahkan pelanggan',
    //             'alert' => 'error',
    //         );
    //         return redirect()->route('admin.reg.index')->with($notifikasi);
    //     }
    // }

    public function update_pelanggan(Request $request, $id)
    {
        $request->validate([
            'reg_stt_perangkat' => 'required',
            'reg_mrek' => 'required',
            'reg_mac' => 'required',
            'reg_sn' => 'required',
            'kode_pactcore' => 'required',
            'kode_adaptor' => 'required',
            'kode_ont_lama' => 'required',
        ], [

            'reg_mrek.required' => 'Merek Perangkat tidak boleh kosong',
            'reg_mac.required' => 'Mac Address tidak boleh kosong',
            'reg_sn.required' => 'Serial Number Perangkat tidak boleh kosong',
            'kode_pactcore.required' => 'Kode Pactcore tidak boleh kosong',
            'kode_adaptor.required' => 'Kode Adaptor tidak boleh kosong',
            'kode_ont_lama.required' => 'Kode ONT tidak boleh kosong',

        ]);


        $data['reg_slotonu'] = $request->reg_slotonu;
        $data['reg_fat'] = $request->reg_odp;
        $data['reg_kode_pactcore'] = $request->kode_pactcore;
        $data['reg_kode_adaptor'] = $request->kode_adaptor;
        $data['reg_catatan'] = $request->reg_catatan;

        if ($request->alasan == 'Rusak') {
            $data['reg_kode_ont'] = $request->kode_ont;
            $data['reg_mrek'] = $request->reg_mrek;
            $data['reg_mac'] = $request->reg_mac;
            $data['reg_sn'] = $request->reg_sn;
            $update_barang['subbarang_status'] = '1';
            $update_barang['subbarang_keluar'] = '1';
            $update_barang['subbarang_stok'] = '0';
            $update_barang['subbarang_keterangan'] = 'Ganti ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . ' Karna Rusak. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_keterangan'] = $request->alasan . ' ' . $request->keterangan;

            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
        } else if ($request->alasan == 'Tukar') {
            $data['reg_kode_ont'] = $request->kode_ont;
            $data['reg_mrek'] = $request->reg_mrek;
            $data['reg_mac'] = $request->reg_mac;
            $data['reg_sn'] = $request->reg_sn;
            $update_barang['subbarang_status'] = '1';
            $update_barang['subbarang_keluar'] = '1';
            $update_barang['subbarang_keterangan'] = 'Tukar ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . '. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_status'] = '0';
            $update_barang_lama['subbarang_keluar'] = '0';
            $update_barang_lama['subbarang_keterangan'] = '-';
            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
        } else if ($request->alasan == 'Upgrade') {
            $data['reg_kode_ont'] = $request->kode_ont;
            $data['reg_mrek'] = $request->reg_mrek;
            $data['reg_mac'] = $request->reg_mac;
            $data['reg_sn'] = $request->reg_sn;
            $update_barang['subbarang_status'] = '1';
            $update_barang['subbarang_keluar'] = '1';
            $update_barang['subbarang_keterangan'] = 'Upgrade ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . '. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_status'] = '0';
            $update_barang_lama['subbarang_keluar'] = '0';
            $update_barang_lama['subbarang_keterangan'] = '-';
            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
        }
        Registrasi::where('reg_idpel', $id)->update($data);

        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id]);
    }



    
    public function update_profile(Request $request, $id)
    {

        $sbiaya = SettingBiaya::first();
        $query = Registrasi::join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        $paket = Paket::where("paket_id", $request->reg_profile)->first();
        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/profile/print', [
                '?name' => $paket->paket_nama,
            ]);
            if ($secret) {
                $cari_pel = $API->comm('/ppp/secret/print', [
                    '?name' => $query->reg_username,
                ]);
                $API->comm('/ppp/secret/set', [
                    '.id' => $cari_pel[0]['.id'],
                    'profile' => $paket->paket_nama,
                ]);
                $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                $data['reg_harga'] = $request->reg_harga;
                $data['reg_ppn'] = $request->reg_ppn;
                $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                $data['reg_kode_unik'] = $request->reg_kode_unik;
                $data['reg_dana_kas'] = $request->reg_dana_kas;
                $data['reg_profile'] = $request->reg_profile;
                if ($request->reg_jenis_tagihan == 'DEPOSIT') {
                    $data['reg_deposit'] = $sbiaya->biaya_deposit;
                } else {
                    $data['reg_deposit'] = '0';
                }
                Registrasi::where('reg_idpel', $id)->update($data);

                $notifikasi = array(
                    'pesan' => 'Berhasil merubah profile pelanggan',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            } else {

                $API->comm('/ip/pool/add', [
                    'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                    'ranges' =>  '10.100.192.100-10.100.207.254' == '' ? '' : '10.100.192.100-10.100.207.254',
                ]);
                $API->comm('/ppp/profile/add', [
                    'name' =>  $paket->paket_nama == '' ? '' : $paket->paket_nama,
                    'rate-limit' => $paket->paket_limitasi == '' ? '' : $paket->paket_limitasi,
                    'local-address' => $paket->paket_lokal == '' ? '' : $paket->paket_lokal,
                    'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                    'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                    'queue-type' => 'default-small' == '' ? '' : 'default-small',
                    'dns-server' => $paket->router_dns == '' ? '' : $paket->router_dns,
                    'only-one' => 'yes',
                ]);
                $cari_pel = $API->comm('/ppp/secret/print', [
                    '?name' => $query->reg_username,
                ]);
                $API->comm('/ppp/secret/set', [
                    '.id' => $cari_pel[0]['.id'],
                    'profile' => $paket->paket_nama,
                ]);

                $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                $data['reg_harga'] = $request->reg_harga;
                $data['reg_ppn'] = $request->reg_ppn;
                $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                $data['reg_kode_unik'] = $request->reg_kode_unik;
                $data['reg_dana_kas'] = $request->reg_dana_kas;
                $data['reg_profile'] = $request->reg_profile;

                if ($request->reg_jenis_tagihan == 'DEPOSIT') {
                    $data['reg_deposit'] = $sbiaya->biaya_deposit;
                } else {
                    $data['reg_deposit'] = '0';
                }

                $notifikasi = array(
                    'pesan' => 'Berhasil merubah profile pelanggan',
                    'alert' => 'success',
                );
                Registrasi::where('reg_idpel', $id)->update($data);
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Router Disconect',
                'alert' => 'error',
            );
            return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
        }
    }
    public function update_router(Request $request, $id)
    {

        $router = Router::whereId($request->reg_router)->first();

        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        // $paket = Paket::where("paket_id", $query->reg_profile)->first();


        $ip =   $router->router_ip . ':' . $router->router_port_api;
        $user = $router->router_username;
        $pass = $router->router_password;
        $API = new RouterosAPI();
        $API->debug = false;

        if ($request->reg_router == $query->reg_router) {
            $before_ip =   $query->router_ip . ':' . $query->router_port_api;
            $before_user = $query->router_username;
            $before_pass = $query->router_password;
            $before_API = new RouterosAPI();
            $before_API->debug = false;

            if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                $before_secret = $before_API->comm('/ppp/secret/print', [
                    '?name' => $query->reg_username,
                ]);
                if ($before_secret) {
                    $before_API->comm('/ppp/secret/set', [
                        '.id' => $before_secret[0]['.id'],
                        'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                    ]);

                    $data['reg_ip_address'] = $request->reg_ip_address;
                    $data['reg_password'] = $request->reg_password;
                    $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                    Registrasi::where('reg_idpel', $id)->update($data);
                    $notifikasi = array(
                        'pesan' => 'Berhasil merubah data Internet',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Gagal merubah data Internet',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Router Disconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            }
        } else {
            if ($API->connect($ip, $user, $pass)) {
                $secret = $API->comm('/ppp/profile/print', [
                    '?name' => $query->paket_nama,
                ]);
                if ($secret) {

                    $API->comm('/ppp/secret/add', [
                        'name' => $query->reg_username == '' ? '' : $query->reg_username,
                        'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                        'service' => 'pppoe',
                        'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                        'comment' => $query->reg_jenis_tagihan == '' ? '' : $query->reg_jenis_tagihan,
                        'disabled' => 'yes',
                    ]);

                    $secret_after = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($secret_after) {
                        $before_ip =   $query->router_ip . ':' . $query->router_port_api;
                        $before_user = $query->router_username;
                        $before_pass = $query->router_password;
                        $before_API = new RouterosAPI();
                        $before_API->debug = false;

                        if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                            $before_secret = $before_API->comm('/ppp/secret/print', [
                                '?name' => $query->reg_username,
                            ]);
                            $before_API->comm('/ppp/secret/remove', [
                                '.id' => $before_secret[0]['.id'],
                            ]);
                            // $data['reg_router'] = $request->reg_router;
                            Registrasi::where('reg_idpel', $id)->update(['reg_router' => $request->reg_router]);
                        }
                    }

                    $notifikasi = array(
                        'pesan' => 'Berhasil merubah router',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                } else {


                    $API->comm('/ip/pool/add', [
                        'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                        'ranges' =>  '10.100.192.100-10.100.207.254' == '' ? '' : '10.100.192.100-10.100.207.254',
                    ]);
                    $API->comm('/ppp/profile/add', [
                        'name' =>  $query->paket_nama == '' ? '' : $query->paket_nama,
                        'rate-limit' => $query->paket_limitasi == '' ? '' : $query->paket_limitasi,
                        'local-address' => $query->paket_lokal == '' ? '' : $query->paket_lokal,
                        'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                        'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                        'queue-type' => 'default-small' == '' ? '' : 'default-small',
                        'dns-server' => $query->router_dns == '' ? '' : $query->router_dns,
                        'only-one' => 'yes',
                    ]);
                    $API->comm('/ppp/secret/add', [
                        'name' => $query->reg_username == '' ? '' : $query->reg_username,
                        'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                        'service' => 'pppoe',
                        'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                        'comment' => $query->reg_jenis_tagihan == '' ? '' : $query->reg_jenis_tagihan,
                        'disabled' => 'yes',
                    ]);

                    $secret_after = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($secret_after) {
                        $before_ip =   $query->router_ip . ':' . $query->router_port_api;
                        $before_user = $query->router_username;
                        $before_pass = $query->router_password;
                        $before_API = new RouterosAPI();
                        $before_API->debug = false;

                        if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                            $before_secret = $before_API->comm('/ppp/secret/print', [
                                '?name' => $query->reg_username,
                            ]);
                            $before_API->comm('/ppp/secret/remove', [
                                '.id' => $before_secret[0]['.id'],
                            ]);
                            Registrasi::where('reg_idpel', $id)->update(['reg_router' => $request->reg_router]);
                        }
                    }

                    $notifikasi = array(
                        'pesan' => 'Berhasil merubah router',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Router Disconect',
                    'alert' => 'error',
                );
                return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
            }
        }
    }
}
