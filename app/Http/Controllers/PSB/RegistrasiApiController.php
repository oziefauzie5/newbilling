<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Barang\SubBarang;
use App\Models\Barang\Barang;
use App\Models\Barang\supplier;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Http\Request;

class RegistrasiApiController extends Controller
{



    public function get_update_tgl_tempo(Request $request, $id)
    {
        $sbiaya = SettingBiaya::first();
        $date1 = Carbon::createFromDate($request->date); // start date

        $valid_date = Carbon::parse($date1)->toDateString();
        $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
        $match_date->setTime(0, 0, 0);

        $diff = $today->diff($match_date);
        $diffDays = (int)$diff->format("%R%a");

        $query = Registrasi::where('registrasis.reg_idpel', $id)
            ->first();

        $biaya_perhari = ($query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama) / 30;
        $harga = $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama;
        $addons = $diffDays * $biaya_perhari;

        if ($query->reg_ppn > 0) {
            $ppn_addons = $sbiaya->biaya_ppn / 100 * $addons;
            $ppn = $query->reg_ppn;
        } else {
            $ppn_addons = 0;
            $ppn = 0;
        }
        // return response()->json($ppn_addons);

        // return response()->json($biaya_perhari);
        if ($diffDays >= 1) {
            if ($request->status >= 1) {
                $data['total_bayar'] = $addons + $query->reg_ppn;
                $data['rincian'] = number_format($biaya_perhari) . ' x ' . $diffDays . ' + PPN ' . number_format($query->reg_ppn);
                $data['status'] = $request->status;
                $data['hari'] = $diffDays;
                $data['biaya'] = $biaya_perhari;
                $data['update_ppn'] = $ppn_addons;
                $data['update_ppn_total'] = $ppn;
            } else {
                $data['total_bayar'] = $harga +  $addons + $ppn_addons + $ppn;
                $data['rincian'] = 'Rp. ' . number_format($biaya_perhari) . ' x ' . $diffDays . ' = Rp. ' . number_format($biaya_perhari * $diffDays) . ' + PPN Rp. ' . number_format($ppn_addons) . ' = Rp. ' . number_format($biaya_perhari * $diffDays + $ppn_addons);
                $data['status'] = 0;
                $data['hari'] = $diffDays;
                $data['biaya'] = $biaya_perhari;
                $data['update_ppn'] = $ppn_addons;
                $data['update_ppn_total'] = $ppn;
            }
            return response()->json($data);
        } else {
            $data['total_bayar'] = 0;
            $data['rincian'] = '-';
            return response()->json($data);
        }
    }

    public function update_tgl_jth_tempo(Request $request, $id)
    {
        $cek_hari = date('d', strtotime($request->reg_tgl_jatuh_tempo));
        $swaktu = SettingWaktuTagihan::first();
        if ($cek_hari == 31) {
            $jeda_waktu = '0';
        } elseif ($cek_hari == 30) {
            $jeda_waktu = '0';
        } else {
            $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
        }
        $tgl_isolir = Carbon::create($request->reg_tgl_jatuh_tempo)->addDay($jeda_waktu)->toDateString();
        $tgl_penagihan = Carbon::create($request->reg_tgl_jatuh_tempo)->addDay(-2)->toDateString();

        $unp = Invoice::where('inv_idpel', $id)->latest('inv_tgl_jatuh_tempo')->first();
        if ($unp) {
            if ($request->status == 0) {
                // $unp = Invoice::where('inv_id', $id)->first();
                $data['subinvoice_id'] = $unp->inv_id;
                $data['subinvoice_deskripsi'] = 'Perubahan jatuh tempo ';
                $data['subinvoice_qty'] = $request->hari;
                $data['subinvoice_harga'] = $request->biaya;
                $data['subinvoice_ppn'] = $request->update_ppn;
                $data['subinvoice_total'] = ($request->biaya * $request->hari) + $request->update_ppn;
                $data['subinvoice_status'] = '1';
                SubInvoice::create($data);
                $update_data['subinvoice_deskripsi'] = date('d-m-Y', strtotime($request->tgl_update)) . ' - ' . date('d-m-Y', strtotime(Carbon::create($request->tgl_update)->addMonth(1)->toDateString()));
                SubInvoice::where('subinvoice_id', $unp->inv_id)->update($update_data);
                $upd['inv_total'] =  $unp->inv_total + $request->update_ppn + ($request->biaya * $request->hari);
                $upd['inv_tgl_jatuh_tempo'] =  date('Y-m-d', strtotime($request->tgl_update));
                $upd['inv_tgl_isolir'] =  date('Y-m-d', strtotime($tgl_isolir));
                $upd['inv_tgl_tagih'] =  date('Y-m-d', strtotime($tgl_penagihan));
                Invoice::where('inv_id', $unp->inv_id)->update($upd);

                $data_reg['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                $data_reg['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                Registrasi::where('reg_idpel', $id)->update($data_reg);
                $notifikasi = array(
                    'pesan' => 'Berhasil update tanggal ',
                    'alert' => 'success',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            } else {
                $data['subinvoice_id'] = $unp->inv_id;
                $data['subinvoice_deskripsi'] = 'Perubahan jatuh tempo ';
                $data['subinvoice_qty'] = $request->hari;
                $data['subinvoice_harga'] = $request->biaya;
                $data['subinvoice_ppn'] = $request->update_ppn;
                $data['subinvoice_total'] = ($request->biaya * $request->hari) + $request->update_ppn;
                $data['subinvoice_status'] = '1';
                $upd['inv_total'] =  $unp->inv_total + $request->update_ppn + ($request->biaya * $request->hari);
                SubInvoice::create($data);
                Invoice::where('inv_id', $unp->inv_id)->update($upd);
                $notifikasi = array(
                    'pesan' => 'Berhasil update tanggal ',
                    'alert' => 'success',
                );
                return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            }
        }
    }


  
    public function update_router(Request $request, $id)
    {
        // dd($id);
        $nama_admin = Auth::user()->name;

        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();

            // dd($query->reg_router);

        // $ip =   $query->router_ip . ':' . $query->router_port_api;
        // $user = $query->router_username;
        // $pass = $query->router_password;
        // $API = new RouterosAPI();
        // $API->debug = false;
        
        // dd($request->reg_router);
        // if ($request->reg_router == $query->reg_router) {
            $before_ip =   $query->router_ip . ':' . $query->router_port_api;
            $before_user = $query->router_username;
            $before_pass = $query->router_password;
            $before_API = new RouterosAPI();
            $before_API->debug = false;
            // dd( $before_API);

            // if ($query->reg_layanan == 'PPP') {

                if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                    // dd($query->reg_username);
                    $before_secret = $before_API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    // dd($before_secret);
                    if ($before_secret) {

                        $before_API->comm('/ppp/secret/set', [
                            '.id' => $before_secret[0]['.id'],
                            'name' => $request->reg_username  == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                        ]);

                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        // $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $before_API->comm('/ppp/secret/add', [
                            'name' => $request->reg_username == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                            'service' => 'pppoe',
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'disabled' => 'no',
                        ]);
                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        // $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                    }
                } else {
                    $notifikasi = array(
                        'pesan' => 'Router Disconect',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
                }
            // } elseif ($query->reg_layanan == 'HOTSPOT') {
            //     if ($before_API->connect($before_ip, $before_user, $before_pass)) {
            //         $before_secret = $before_API->comm('/ip/hotspot/user/print', [
            //             '?name' => $query->reg_username,
            //         ]);
            //         if ($before_secret) {

            //             $before_API->comm('/ip/hotspot/user/set', [
            //                 '.id' => $before_secret[0]['.id'],
            //                 'name' => $request->reg_username  == '' ? '' : $request->reg_username,
            //                 'password' => $request->reg_password  == '' ? '' : $request->reg_password,
            //             ]);

            //             $data['reg_ip_address'] = $request->reg_ip_address;
            //             $data['reg_username'] = $request->reg_username;
            //             $data['reg_password'] = $request->reg_password;
            //             // $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
            //             Registrasi::where('reg_idpel', $id)->update($data);
            //             $notifikasi = array(
            //                 'pesan' => 'Berhasil merubah data Internet',
            //                 'alert' => 'success',
            //             );
            //             return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            //         } else {
            //             $before_API->comm('/ip/hotspot/user/add', [
            //                 'name' => $request->reg_username == '' ? '' : $request->reg_username,
            //                 'password' => $request->reg_password  == '' ? '' : $request->reg_password,
            //                 'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
            //                 'disabled' => 'no',
            //             ]);
            //             $data['reg_ip_address'] = $request->reg_ip_address;
            //             $data['reg_username'] = $request->reg_username;
            //             $data['reg_password'] = $request->reg_password;
            //             // $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
            //             Registrasi::where('reg_idpel', $id)->update($data);
            //             $notifikasi = array(
            //                 'pesan' => 'Berhasil merubah data Internet',
            //                 'alert' => 'success',
            //             );
            //             return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            //         }
            //     } else {
            //         $notifikasi = array(
            //             'pesan' => 'Router Disconect',
            //             'alert' => 'error',
            //         );
            //         return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
            //     }
            // }
        // } else {

        //     if ($query->reg_layanan == 'PPP') {
        //         if ($API->connect($ip, $user, $pass)) {
        //             $secret = $API->comm('/ppp/profile/print', [
        //                 '?name' => $query->paket_nama,
        //             ]);
        //             if ($secret) {

        //                 $API->comm('/ppp/secret/add', [
        //                     'name' => $query->reg_username == '' ? '' : $query->reg_username,
        //                     'password' => $query->reg_password  == '' ? '' : $query->reg_password,
        //                     'service' => 'pppoe',
        //                     'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
        //                     'disabled' => 'no',
        //                 ]);

        //                 $secret_after = $API->comm('/ppp/secret/print', [
        //                     '?name' => $query->reg_username,
        //                 ]);
        //                 if ($secret_after) {
        //                     $before_ip =   $query->router_ip . ':' . $query->router_port_api;
        //                     $before_user = $query->router_username;
        //                     $before_pass = $query->router_password;
        //                     $before_API = new RouterosAPI();
        //                     $before_API->debug = false;

        //                     if ($before_API->connect($before_ip, $before_user, $before_pass)) {
        //                         $before_secret = $before_API->comm('/ppp/secret/print', [
        //                             '?name' => $query->reg_username,
        //                         ]);
        //                         if ($before_secret) {
        //                             $before_API->comm('/ppp/secret/remove', [
        //                                 '.id' => $before_secret[0]['.id'],
        //                             ]);
        //                         }
        //                         Registrasi::where('reg_idpel', $id)->update([
        //                             'reg_username' => $request->reg_username,
        //                             'reg_password' => $request->reg_password,
        //                         ]);
        //                     }
        //                 }

        //                 $notifikasi = array(
        //                     'pesan' => 'Berhasil merubah router',
        //                     'alert' => 'success',
        //                 );
        //                 return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //             } else {


        //                 $API->comm('/ip/pool/add', [
        //                     'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
        //                     'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
        //                 ]);
        //                 $API->comm('/ppp/profile/add', [
        //                     'name' =>  $query->paket_nama == '' ? '' : $query->paket_nama,
        //                     'rate-limit' => $query->paket_limitasi == '' ? '' : $query->paket_limitasi,
        //                     'local-address' => $query->paket_lokal == '' ? '' : $query->paket_lokal,
        //                     'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
        //                     'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
        //                     'queue-type' => 'default-small' == '' ? '' : 'default-small',
        //                     'dns-server' => $query->router_dns == '' ? '' : $query->router_dns,
        //                     'only-one' => 'yes',
        //                     'disabled' => 'no',
        //                 ]);
        //                 $API->comm('/ppp/secret/add', [
        //                     'name' => $query->reg_username == '' ? '' : $query->reg_username,
        //                     'password' => $query->reg_password  == '' ? '' : $query->reg_password,
        //                     'service' => 'pppoe',
        //                     'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
        //                     'comment' => $comment == '' ? '' : $comment,
        //                     'disabled' => 'no',
        //                 ]);

        //                 $secret_after = $API->comm('/ppp/secret/print', [
        //                     '?name' => $query->reg_username,
        //                 ]);
        //                 if ($secret_after) {
        //                     $before_ip =   $query->router_ip . ':' . $query->router_port_api;
        //                     $before_user = $query->router_username;
        //                     $before_pass = $query->router_password;
        //                     $before_API = new RouterosAPI();
        //                     $before_API->debug = false;

        //                     if ($before_API->connect($before_ip, $before_user, $before_pass)) {
        //                         $before_secret = $before_API->comm('/ppp/secret/print', [
        //                             '?name' => $query->reg_username,
        //                         ]);
        //                         if ($before_secret) {
        //                             $before_API->comm('/ppp/secret/remove', [
        //                                 '.id' => $before_secret[0]['.id'],
        //                             ]);
        //                              Registrasi::where('reg_idpel', $id)->update([
        //                             'reg_username' => $request->reg_username,
        //                             'reg_password' => $request->reg_password,
        //                         ]);
        //                         }
        //                     }
        //                 }

        //                 $notifikasi = array(
        //                     'pesan' => 'Berhasil merubah router',
        //                     'alert' => 'success',
        //                 );
        //                 return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //             }
        //         } else {
        //             $notifikasi = array(
        //                 'pesan' => 'Router Disconect',
        //                 'alert' => 'error',
        //             );
        //             return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //         }
        //     } elseif ($query->reg_layanan == 'HOTSPOT') {
        //         if ($API->connect($ip, $user, $pass)) {
        //             $secret = $API->comm('/ip/hotspot/user/profile/print', [
        //                 '?name' => $query->paket_nama,
        //             ]);
        //             if ($secret) {
        //                 $API->comm('/ip/hotspot/user/add', [
        //                     'name' => $query->reg_username == '' ? '' : $query->reg_username,
        //                     'password' => $query->reg_password  == '' ? '' : $query->reg_password,
        //                     'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
        //                     'comment' => $comment  == '' ? '' : $comment,
        //                     'disabled' => 'no',
        //                 ]);

        //                 $secret_after = $API->comm('/ip/hotspot/user/print', [
        //                     '?name' => $query->reg_username,
        //                 ]);
        //                 if ($secret_after) {
        //                     $before_ip =   $query->router_ip . ':' . $query->router_port_api;
        //                     $before_user = $query->router_username;
        //                     $before_pass = $query->router_password;
        //                     $before_API = new RouterosAPI();
        //                     $before_API->debug = false;

        //                     if ($before_API->connect($before_ip, $before_user, $before_pass)) {
        //                         $before_secret = $before_API->comm('/ip/hotspot/user/print', [
        //                             '?name' => $query->reg_username,
        //                         ]);
        //                         if ($before_secret) {
        //                             $before_API->comm('/ip/hotspot/active/remove', [
        //                                 '.id' => $before_secret[0]['.id'],
        //                             ]);
        //                         }
        //                         Registrasi::where('reg_idpel', $id)->update(['reg_router' => $request->reg_router]);
        //                     }
        //                 }

        //                 $notifikasi = array(
        //                     'pesan' => 'Berhasil merubah router',
        //                     'alert' => 'success',
        //                 );
        //                 return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //             } else {

        //                 $notifikasi = array(
        //                     'pesan' => 'Gagal edit router. Paket tidak tersedia pada router ini',
        //                     'alert' => 'error',
        //                 );
        //                 return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //             }
        //         } else {
        //             $notifikasi = array(
        //                 'pesan' => 'Router Disconect',
        //                 'alert' => 'error',
        //             );
        //             return redirect()->route('admin.reg.form_update_pelanggan', ['id' => $id])->with($notifikasi);
        //         }
        //     }
        // }
    }
}
