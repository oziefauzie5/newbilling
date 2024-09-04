<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Barang\SubBarang;
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

        if ($regist->reg_jenis_tagihan == 'FREE') {
            $comment = 'FREE';
        } else {
            $comment = 'MIGRASI';
        }

        if ($API->connect($ip, $user, $pass)) {

            $API->comm('/ip/pool/add', [
                'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
            ]);
            $API->comm('/ppp/profile/add', [
                'name' =>  $regist->paket_nama == '' ? '' : $regist->paket_nama,
                'rate-limit' => $regist->paket_nama == '' ? '' : $regist->paket_nama,
                'local-address' => $regist->paket_lokal == '' ? '' : $regist->paket_lokal,
                'remote-address' => 'APPBILL' == '' ? '' : 'APPBILL',
                'comment' => 'default by appbill ( jangan diubah )' == '' ? '' : 'default by appbill ( jangan diubah )',
                'queue-type' => 'default-small' == '' ? '' : 'default-small',
                'dns-server' => $router->router_dns == '' ? '' : $router->router_dns,
                'only-one' => 'yes',
            ]);
            $profile = $API->comm('/ppp/profile/print', [
                '?name' => $regist->paket_nama,
            ]);
            if ($profile) {
                $API->comm('/ppp/secret/add', [
                    'name' => $regist->reg_username == '' ? '' : $regist->reg_username,
                    'password' => $regist->reg_password  == '' ? '' : $regist->reg_password,
                    'service' => 'pppoe',
                    'profile' => $regist->paket_nama  == '' ? 'default' : $regist->paket_nama,
                    'comment' => $comment == '' ? '' : $comment,
                    'disabled' => 'no',
                ]);

                $notifikasi = array(
                    'pesan' => 'Berhasil menambahkan pelanggan',
                    'alert' => 'success',
                );
                return redirect()->route('admin.psb.index')->with($notifikasi);
            } else {
                $notifikasi = array(
                    'pesan' => 'Gagal menambah pelanggan..Paket Tidak tersedia pada router',
                    'alert' => 'error',
                );
                return redirect()->route('admin.psb.index')->with($notifikasi);
            }
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal menambahkan pelanggan',
                'alert' => 'error',
            );
            return redirect()->route('admin.reg.index')->with($notifikasi);
        }
    }


    public function update_pelanggan(Request $request, $id)
    {
        $nama_admin = Auth::user()->name;
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
            $update_barang['subbarang_admin'] = $nama_admin;
            $update_barang['subbarang_keterangan'] = 'Ganti ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . ' Karna Rusak. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_keterangan'] = $request->alasan . ' ' . $request->keterangan;

            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
        } elseif ($request->alasan == 'Tukar') {
            $data['reg_kode_ont'] = $request->kode_ont;
            $data['reg_mrek'] = $request->reg_mrek;
            $data['reg_mac'] = $request->reg_mac;
            $data['reg_sn'] = $request->reg_sn;
            $update_barang['subbarang_status'] = '1';
            $update_barang['subbarang_keluar'] = '1';
            $update_barang['subbarang_stok'] = '0';
            $update_barang['subbarang_admin'] = $nama_admin;
            $update_barang['subbarang_keterangan'] = 'Tukar ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . '. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_status'] = '0';
            $update_barang_lama['subbarang_keluar'] = '0';
            $update_barang_lama['subbarang_stok'] = '1';
            $update_barang_lama['subbarang_keterangan'] = 'Tukar Barang';
            $update_barang_lama['subbarang_admin'] = $nama_admin;
            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
        } elseif ($request->alasan == 'Upgrade') {
            $data['reg_kode_ont'] = $request->kode_ont;
            $data['reg_mrek'] = $request->reg_mrek;
            $data['reg_mac'] = $request->reg_mac;
            $data['reg_sn'] = $request->reg_sn;
            $update_barang['subbarang_status'] = '1';
            $update_barang['subbarang_keluar'] = '1';
            $update_barang['subbarang_stok'] = '0';
            $update_barang['subbarang_admin'] = $nama_admin;
            $update_barang['subbarang_keterangan'] = 'Upgrade ONT ' . $request->kode_ont_lama . ' Pel. ' . $request->reg_nama . '. ( ' . $request->keterangan . ' )';
            $update_barang_lama['subbarang_status'] = '0';
            $update_barang_lama['subbarang_keluar'] = '0';
            $update_barang_lama['subbarang_stok'] = '1';
            $update_barang_lama['subbarang_keterangan'] = '-';
            $update_barang_lama['subbarang_admin'] = $nama_admin;
            Registrasi::where('reg_idpel', $id)->update($data);
            SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
            SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
        }
        Registrasi::where('reg_idpel', $id)->update($data);

        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id]);
    }




    public function update_profile(Request $request, $id)
    {

        // $data['reg_tgl_pasang'] = date('Y-m-d',strtotime($request->tgl_pasang));
        // Registrasi::where('reg_idpel', $id)->update($data);
        // $notifikasi = array(
        //     'pesan' => 'Berhasil',
        //     'alert' => 'success',
        // );
        // return redirect()->route('admin.reg.index')->with($notifikasi);

        $nama_admin = Auth::user()->name;
        $hari_ini = date('Y-m-d', strtotime('2024-07-15'));
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $cek_hari = date('d', strtotime($request->reg_tgl_jatuh_tempo));


        $sbiaya = SettingBiaya::first();
        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        $swaktu = SettingWaktuTagihan::first();
        if ($cek_hari == 31) {
            $jeda_waktu = '0';
        } elseif ($cek_hari == 30) {
            $jeda_waktu = '0';
        } else {
            $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
        }
        $tgl_isolir = Carbon::create($request->reg_tgl_jatuh_tempo)->addDay($jeda_waktu)->toDateString();
        $telat_tgl_isolir =  Carbon::create($query->reg_tgl_jatuh_tempo)->addDay($jeda_waktu)->toDateString();


        // dd($tgl_isolir);
        // dd($tgl_isolir);
        $tgl_penagihan = Carbon::create($request->reg_tgl_jatuh_tempo)->addDay(-2)->toDateString();
        $cek_invid = Invoice::where('inv_idpel', $id)->latest('inv_tgl_jatuh_tempo')->first();
        $periode = date('d-m-Y', strtotime(Carbon::create($request->reg_tgl_jatuh_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($request->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString()));

        $dikurang_1_bulan = Carbon::create($request->reg_tgl_jatuh_tempo)->addMonth(-1)->toDateString();

        // HITUNG TELAT PEMBAYARAN
        $date1 = Carbon::createFromDate($request->reg_tgl_jatuh_tempo); // start date
        // $date2 = Carbon::createFromDate($hari_ini); // end date
        // $monthDifference = $date1->diffInMonths($date2);
        // $diffInDays = $date1->diffInDays($date2);

        // ------------------------
        $valid_date = Carbon::parse($date1)->toDateString();
        $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
        $match_date->setTime(0, 0, 0);

        $diff = $today->diff($match_date);
        $diffDays = (int)$diff->format("%R%a");

        // dd($diffDays);
        // ------------------------

        // if ($diffDays < -0) {
        //     dd($diffDays.' SUSPEND');
        // } else{
        //     dd($diffDays.' UNPAID');
        // } 
        // dd($diffDays);

        $paid_periode = date('d-m-Y', strtotime(Carbon::create($dikurang_1_bulan)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($dikurang_1_bulan)->addMonth(1)->toDateString()));
        $paid_tgl_isolir = Carbon::create($dikurang_1_bulan)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
        $paid_tgl_penagihan = Carbon::create($dikurang_1_bulan)->addDay(-2)->toDateString();


        $hari_jt_tempo = date('d', strtotime($request->reg_tgl_jatuh_tempo));
        $telat_tgl_tagih = date($year . '-' . $month . '-d', strtotime(Carbon::create($request->reg_tgl_jatuh_tempo)->addDay(-2)->toDateString()));
        $telat_periode = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
        $telat_tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($request->reg_tgl_jatuh_tempo));


        // dd($telat_tgl_isolir);

        $ip =   $query->router_ip . ':' . $query->router_port_api;
        $user = $query->router_username;
        $pass = $query->router_password;
        $API = new RouterosAPI();
        $API->debug = false;


        if ($request->reg_jenis_tagihan == 'FREE') {
            $comment = 'FREE Update-Profile-By:' . $nama_admin;
        } else {
            $comment = 'Update-Profile By: ' . $nama_admin . ' Jatuh-Tempo :' . date('Y-m-d', strtotime($query->reg_tgl_jatuh_tempo));
        }
        if ($query->reg_layanan == 'PPP') {
            if ($API->connect($ip, $user, $pass)) {
                $secret = $API->comm('/ppp/profile/print', [
                    '?name' => $query->paket_nama,
                ]);
                if ($secret) {
                    $cari_pel = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($cari_pel) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $cari_pel[0]['.id'],
                            'profile' => $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                        ]);
                        if ($request->reg_jenis_tagihan == 'DEPOSIT') {
                            $data['reg_deposit'] = $sbiaya->biaya_deposit;
                        } else {
                            $data['reg_deposit'] = '0';
                        }

                        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                        $data['reg_harga'] = $request->reg_harga;
                        $data['reg_ppn'] = $request->reg_ppn;
                        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                        $data['reg_kode_unik'] = $request->reg_kode_unik;
                        $data['reg_dana_kas'] = $request->reg_dana_kas;
                        $data['reg_profile'] = $request->reg_profile;
                        $data['reg_inv_control'] = $request->reg_inv_control;
                        if ($cek_invid) {
                            if ($cek_invid->inv_status != 'PAID') {

                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                // if (date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo)) <= $hari_ini) {

                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'SUSPEND';
                                    $update_inv['inv_status'] = 'SUSPEND';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'UNPAID';
                                    $update_inv['inv_status'] = 'UNPAID';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $periode;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }



                                $update_inv['inv_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_harga'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_ppn'] = $request->reg_ppn;
                                $update_subinv['subinvoice_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            } else {
                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                $data['reg_status'] = $cek_invid->inv_status;
                                $update_inv['inv_status'] = $cek_invid->inv_status;

                                $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($paid_tgl_penagihan));
                                $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($dikurang_1_bulan));
                                $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($paid_tgl_isolir));
                                $update_inv['inv_periode'] = $paid_periode;
                                $update_subinv['subinvoice_deskripsi'] = $paid_periode;
                                // dd($update_subinv);
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            }
                        }
                        $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                        $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                        Registrasi::where('reg_idpel', $id)->update($data);

                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah profile pelanggan',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $API->comm('/ppp/secret/add', [
                            'name' => $query->reg_username == '' ? '' : $query->reg_username,
                            'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                            'service' => 'pppoe',
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                            'disabled' => 'no',
                        ]);
                        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                        $data['reg_harga'] = $request->reg_harga;
                        $data['reg_ppn'] = $request->reg_ppn;
                        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                        $data['reg_kode_unik'] = $request->reg_kode_unik;
                        $data['reg_dana_kas'] = $request->reg_dana_kas;
                        $data['reg_profile'] = $request->reg_profile;
                        $data['reg_inv_control'] = $request->reg_inv_control;
                        $data['reg_status'] = $request->reg_status;
                        if ($cek_invid) {
                            if ($cek_invid->inv_status != 'PAID') {


                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'SUSPEND';
                                    $update_inv['inv_status'] = 'SUSPEND';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'UNPAID';
                                    $update_inv['inv_status'] = 'UNPAID';
                                    $update_inv['inv_periode'] = $periode;
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }


                                $update_inv['inv_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_harga'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_ppn'] = $request->reg_ppn;
                                $update_subinv['subinvoice_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            } else {
                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                $data['reg_status'] = $cek_invid->inv_status;
                                $update_inv['inv_status'] = $cek_invid->inv_status;

                                $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($paid_tgl_penagihan));
                                $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($dikurang_1_bulan));
                                $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($paid_tgl_isolir));
                                $update_inv['inv_periode'] = $paid_periode;
                                $update_subinv['subinvoice_deskripsi'] = $paid_periode;
                                // dd($update_subinv);
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            }
                        }
                        $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                        $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
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
                    }
                } else {

                    $API->comm('/ip/pool/add', [
                        'name' =>  'APPBILL' == '' ? '' : 'APPBILL',
                        'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
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
                    $cari_pel = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    // dd( $cari_pel );

                    $API->comm('/ppp/secret/set', [
                        '.id' => $cari_pel[0]['.id'],
                        'profile' => $query->paket_nama,
                        'comment' => $comment == '' ? '' : $comment,
                    ]);

                    $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                    $data['reg_harga'] = $request->reg_harga;
                    $data['reg_ppn'] = $request->reg_ppn;
                    $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                    $data['reg_kode_unik'] = $request->reg_kode_unik;
                    $data['reg_dana_kas'] = $request->reg_dana_kas;
                    $data['reg_profile'] = $request->reg_profile;
                    $data['reg_inv_control'] = $request->reg_inv_control;
                    if ($cek_invid) {
                        if ($cek_invid->inv_status != 'PAID') {


                            $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                            $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                            if (date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo)) <= $hari_ini) {

                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'ISOLIR';
                                    $update_inv['inv_status'] = 'ISOLIR';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'SUSPEND';
                                    $update_inv['inv_status'] = 'SUSPEND';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $periode;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }
                            } elseif (date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo)) >= $hari_ini) {
                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'ISOLIR';
                                    $update_inv['inv_status'] = 'ISOLIR';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'UNPAID';
                                    $update_inv['inv_status'] = 'UNPAID';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $periode;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }
                            }


                            $update_inv['inv_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                            $update_subinv['subinvoice_harga'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                            $update_subinv['subinvoice_ppn'] = $request->reg_ppn;
                            $update_subinv['subinvoice_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                            SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                            Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                        } else {
                            $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                            $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                            $data['reg_status'] = $cek_invid->inv_status;
                            $update_inv['inv_status'] = $cek_invid->inv_status;

                            $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                            $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($paid_tgl_penagihan));
                            $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($dikurang_1_bulan));
                            $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($paid_tgl_isolir));
                            $update_inv['inv_periode'] = $paid_periode;
                            $update_subinv['subinvoice_deskripsi'] = $paid_periode;
                            // dd($update_subinv);
                            SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                            Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                        }
                    }
                    $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                    $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

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
        } else {
            #LAYANAN HOTSPOT
            if ($API->connect($ip, $user, $pass)) {
                $secret = $API->comm('/ip/hotspot/user/profile/print', [
                    '?name' => $query->paket_nama,
                ]);
                // dd('ciluk');
                if ($secret) {
                    $cari_pel = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($cari_pel) {

                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cari_pel[0]['.id'],
                            'profile' => $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                        ]);
                        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                        $data['reg_harga'] = $request->reg_harga;
                        $data['reg_ppn'] = $request->reg_ppn;
                        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                        $data['reg_kode_unik'] = $request->reg_kode_unik;
                        $data['reg_dana_kas'] = $request->reg_dana_kas;
                        $data['reg_profile'] = $request->reg_profile;
                        $data['reg_inv_control'] = $request->reg_inv_control;
                        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                        $data['reg_harga'] = $request->reg_harga;
                        $data['reg_ppn'] = $request->reg_ppn;
                        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                        $data['reg_kode_unik'] = $request->reg_kode_unik;
                        $data['reg_dana_kas'] = $request->reg_dana_kas;
                        $data['reg_profile'] = $request->reg_profile;
                        $data['reg_inv_control'] = $request->reg_inv_control;

                        if ($cek_invid) {
                            if ($cek_invid->inv_status != 'PAID') {


                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'SUSPEND';
                                    $update_inv['inv_status'] = 'SUSPEND';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'UNPAID';
                                    $update_inv['inv_status'] = 'UNPAID';
                                    $update_inv['inv_periode'] = $periode;
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }


                                $update_inv['inv_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_harga'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_ppn'] = $request->reg_ppn;
                                $update_subinv['subinvoice_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            } else {
                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                $data['reg_status'] = $cek_invid->inv_status;
                                $update_inv['inv_status'] = $cek_invid->inv_status;

                                $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($paid_tgl_penagihan));
                                $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($dikurang_1_bulan));
                                $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($paid_tgl_isolir));
                                $update_inv['inv_periode'] = $paid_periode;
                                $update_subinv['subinvoice_deskripsi'] = $paid_periode;
                                // dd($update_subinv);
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            }
                        }
                        $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                        $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
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
                        $API->comm('/ip/hotspot/user/add', [
                            'name' => $query->reg_username == '' ? '' : $query->reg_username,
                            'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                            'disabled' => 'no',
                        ]);
                        $data['reg_jenis_tagihan'] = $request->reg_jenis_tagihan;
                        $data['reg_harga'] = $request->reg_harga;
                        $data['reg_ppn'] = $request->reg_ppn;
                        $data['reg_dana_kerjasama'] = $request->reg_dana_kerjasama;
                        $data['reg_kode_unik'] = $request->reg_kode_unik;
                        $data['reg_dana_kas'] = $request->reg_dana_kas;
                        $data['reg_profile'] = $request->reg_profile;
                        $data['reg_inv_control'] = $request->reg_inv_control;
                        // dd('ciluk1');
                        if ($cek_invid) {
                            if ($cek_invid->inv_status != 'PAID') {


                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                if ($diffDays < -0) {
                                    $data['reg_status'] = 'SUSPEND';
                                    $update_inv['inv_status'] = 'SUSPEND';
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_inv['inv_periode'] = $telat_periode;
                                    $update_subinv['subinvoice_deskripsi'] = $telat_periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($telat_tgl_tagih));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($telat_tgl_jt_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($telat_tgl_isolir));
                                } else {
                                    $data['reg_status'] = 'UNPAID';
                                    $update_inv['inv_status'] = 'UNPAID';
                                    $update_inv['inv_periode'] = $periode;
                                    $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                    $update_subinv['subinvoice_deskripsi'] = $periode;
                                    $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                    $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
                                    $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($tgl_isolir));
                                }


                                $update_inv['inv_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_harga'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                $update_subinv['subinvoice_ppn'] = $request->reg_ppn;
                                $update_subinv['subinvoice_total'] = $request->reg_harga + $request->reg_kode_unik + $request->reg_ppn + $request->reg_dana_kas + $request->reg_dana_kerja_sama;
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            } else {
                                $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                                $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));

                                $data['reg_status'] = $cek_invid->inv_status;
                                $update_inv['inv_status'] = $cek_invid->inv_status;

                                $update_inv['inv_jenis_tagihan'] = $request->reg_jenis_tagihan;
                                $update_inv['inv_tgl_tagih'] = date('Y-m-d', strtotime($paid_tgl_penagihan));
                                $update_inv['inv_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($dikurang_1_bulan));
                                $update_inv['inv_tgl_isolir'] = date('Y-m-d', strtotime($paid_tgl_isolir));
                                $update_inv['inv_periode'] = $paid_periode;
                                $update_subinv['subinvoice_deskripsi'] = $paid_periode;
                                // dd($update_subinv);
                                SubInvoice::where('subinvoice_id', $cek_invid->inv_id)->update($update_subinv);
                                Invoice::where('inv_id', $cek_invid->inv_id)->update($update_inv);
                            }
                        }
                        $data['reg_tgl_tagih'] = date('Y-m-d', strtotime($tgl_penagihan));
                        $data['reg_tgl_jatuh_tempo'] = date('Y-m-d', strtotime($request->reg_tgl_jatuh_tempo));
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
                    }
                } else {

                    $notifikasi = array(
                        'pesan' => 'Paket belum tersedia pada Router ini',
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
        }
    }
    public function update_router(Request $request, $id)
    {
        $nama_admin = Auth::user()->name;
        $router = Router::whereId($request->reg_router)->first();

        $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        // $paket = Paket::where("paket_id", $query->reg_profile)->first();
        if ($query->reg_jenis_tagihan == 'FREE') {
            $comment = '( Done ) FREE  ';
        } else {
            $comment = '( Done ) ' . date('Y-m-d', strtotime($query->reg_tgl_jatuh_tempo));
        }

        if ($query->reg_jenis_tagihan == 'FREE') {
            $comment = 'FREE Update-Router-By:' . $nama_admin;
        } else {
            $comment = 'Update-Router By: ' . $nama_admin . ' Jatuh-Tempo :' . date('Y-m-d', strtotime($query->reg_tgl_jatuh_tempo));
        }

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

            if ($query->reg_layanan == 'PPP') {

                if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                    $before_secret = $before_API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($before_secret) {

                        $before_API->comm('/ppp/secret/set', [
                            '.id' => $before_secret[0]['.id'],
                            'name' => $request->reg_username  == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                            'comment' => $comment == '' ? '' : $comment,
                        ]);

                        $data['reg_ip_address'] = $request->reg_ip_address;
                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $before_API->comm('/ppp/secret/add', [
                            'name' => $request->reg_username == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                            'service' => 'pppoe',
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                            'disabled' => 'no',
                        ]);
                        $data['reg_ip_address'] = $request->reg_ip_address;
                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
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
            } elseif ($query->reg_layanan == 'HOTSPOT') {
                if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                    $before_secret = $before_API->comm('/ip/hotspot/user/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($before_secret) {

                        $before_API->comm('/ip/hotspot/user/set', [
                            '.id' => $before_secret[0]['.id'],
                            'name' => $request->reg_username  == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                            'comment' => $comment == '' ? '' : $comment,
                        ]);

                        $data['reg_ip_address'] = $request->reg_ip_address;
                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {
                        $before_API->comm('/ip/hotspot/user/add', [
                            'name' => $request->reg_username == '' ? '' : $request->reg_username,
                            'password' => $request->reg_password  == '' ? '' : $request->reg_password,
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment  == '' ? '' : $comment,
                            'disabled' => 'no',
                        ]);
                        $data['reg_ip_address'] = $request->reg_ip_address;
                        $data['reg_username'] = $request->reg_username;
                        $data['reg_password'] = $request->reg_password;
                        $data['reg_stt_perangkat'] = $request->reg_stt_perangkat;
                        Registrasi::where('reg_idpel', $id)->update($data);
                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah data Internet',
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
        } else {

            if ($query->reg_layanan == 'PPP') {
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
                            'comment' => $comment == '' ? '' : $comment,
                            'disabled' => 'no',
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
                                if ($before_secret) {
                                    $before_API->comm('/ppp/secret/remove', [
                                        '.id' => $before_secret[0]['.id'],
                                    ]);
                                }
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
                            'ranges' =>  '10.100.100.254-10.100.107.254' == '' ? '' : '10.100.100.254-10.100.107.254',
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
                            'disabled' => 'no',
                        ]);
                        $API->comm('/ppp/secret/add', [
                            'name' => $query->reg_username == '' ? '' : $query->reg_username,
                            'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                            'service' => 'pppoe',
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment == '' ? '' : $comment,
                            'disabled' => 'no',
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
                                if ($before_secret) {
                                    $before_API->comm('/ppp/secret/remove', [
                                        '.id' => $before_secret[0]['.id'],
                                    ]);
                                    Registrasi::where('reg_idpel', $id)->update(['reg_router' => $request->reg_router]);
                                }
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
            } elseif ($query->reg_layanan == 'HOTSPOT') {
                if ($API->connect($ip, $user, $pass)) {
                    $secret = $API->comm('/ip/hotspot/user/profile/print', [
                        '?name' => $query->paket_nama,
                    ]);
                    if ($secret) {
                        $API->comm('/ip/hotspot/user/add', [
                            'name' => $query->reg_username == '' ? '' : $query->reg_username,
                            'password' => $query->reg_password  == '' ? '' : $query->reg_password,
                            'profile' => $query->paket_nama  == '' ? 'default' : $query->paket_nama,
                            'comment' => $comment  == '' ? '' : $comment,
                            'disabled' => 'no',
                        ]);

                        $secret_after = $API->comm('/ip/hotspot/user/print', [
                            '?name' => $query->reg_username,
                        ]);
                        if ($secret_after) {
                            $before_ip =   $query->router_ip . ':' . $query->router_port_api;
                            $before_user = $query->router_username;
                            $before_pass = $query->router_password;
                            $before_API = new RouterosAPI();
                            $before_API->debug = false;

                            if ($before_API->connect($before_ip, $before_user, $before_pass)) {
                                $before_secret = $before_API->comm('/ip/hotspot/user/print', [
                                    '?name' => $query->reg_username,
                                ]);
                                if ($before_secret) {
                                    $before_API->comm('/ip/hotspot/active/remove', [
                                        '.id' => $before_secret[0]['.id'],
                                    ]);
                                }
                                Registrasi::where('reg_idpel', $id)->update(['reg_router' => $request->reg_router]);
                            }
                        }

                        $notifikasi = array(
                            'pesan' => 'Berhasil merubah router',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.psb.edit_pelanggan', ['id' => $id])->with($notifikasi);
                    } else {

                        $notifikasi = array(
                            'pesan' => 'Gagal edit router. Paket tidak tersedia pada router ini',
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
            }
        }
    }
}
