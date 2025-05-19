<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use App\Models\Hotspot\Data_Voucher;
use App\Models\Hotspot\Data_Outlet;
use App\Models\Hotspot\Pesanan_Voucher;
use App\Models\Hotspot\Data_Pesanan;
use App\Models\Hotspot\Data_Bagihasil;
use App\Models\Mitra\MitraSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Site;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use Carbon\Carbon;
use App\Models\Transaksi\Laporan;
use App\Models\Mitra\Mutasi;
use Illuminate\Support\Facades\DB;
use App\Models\Router\RouterosAPI;

class HotspotController extends Controller
{
    public function data_voucher(Request $request)
    {
        // dd(Data_Voucher::where('vhc_status_pakai','=',NULL)->count());
        // Data_Voucher::where('vhc_status_pakai','=',NULL)->update([
        //     'vhc_status_pakai' => 'Belum Terpakai',
        // ]);
        $data['q'] = $request->query('q');
        $query = Data_Voucher::where('vhc_status', 'Enable')
            ->join('routers', 'routers.id', '=', 'data__vouchers.vhc_router')
            ->join('pakets', 'pakets.paket_id', '=', 'data__vouchers.vhc_paket')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__vouchers.vhc_outlet')
            ->join('users', 'users.id', '=', 'data__vouchers.vhc_mitra')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__vouchers.vhc_site')
            ->where('data__vouchers.vhc_status_pakai', '=', 'Belum Terpakai')
            ->where(function ($query) use ($data) {
                $query->where('data__vouchers.vhc_username', 'like', '%' . $data['q'] . '%');
                $query->where('data__outlets.outlet_nama', 'like', '%' . $data['q'] . '%');
            });
        $data['data_voucher'] = $query->paginate(10);


        $data['data_mitra'] = MitraSetting::join('users', 'users.id', '=', 'mitra_settings.mts_user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.role_id', '14')
            ->where('users.status_user', 'Enable')
            ->get();
        $data['data_paket'] = Paket::where('paket_layanan', 'VOUCHER')->where('paket_status', 'Enable')->get();
        $data['data_outlet'] = Data_Outlet::where('outlet_status', 'Enable')->get();
        $data['data_router'] = Router::all();
        return view('hotspot/data_voucher', $data);
    }
    public function data_voucher_terjual(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Data_Voucher::where('vhc_status', 'Enable')
            ->join('routers', 'routers.id', '=', 'data__vouchers.vhc_router')
            ->join('pakets', 'pakets.paket_id', '=', 'data__vouchers.vhc_paket')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__vouchers.vhc_outlet')
            ->join('users', 'users.id', '=', 'data__vouchers.vhc_mitra')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__vouchers.vhc_site')
            ->where('data__vouchers.vhc_status_pakai', '=', 'Terpakai')
            ->where(function ($query) use ($data) {
                $query->where('data__vouchers.vhc_username', 'like', '%' . $data['q'] . '%');
            });
        $data['data_voucher'] = $query->paginate(10);

        return view('hotspot/data_voucher_terjual', $data);
    }


    public function form_pesanan_voucher()
    {
        $user_admin = (new GlobalController)->user_admin();
        $data['user_id'] = $user_admin['user_id'];
        $data['user_nama'] = $user_admin['user_nama'];
        $data['data_mitra'] = MitraSetting::join('users', 'users.id', '=', 'mitra_settings.mts_user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.role_id', '14')
            ->where('users.status_user', 'Enable')
            ->get();
        $data['data_paket'] = Paket::where('paket_layanan', 'VOUCHER')->where('paket_status', 'Enable')->get();
        $data['data_outlet'] = Data_Outlet::where('outlet_status', 'Enable')->get();
        $data['data_router'] = Router::all();
        $data['data_site'] = Data_Site::all();
        return view('hotspot/form_pesanan_voucher', $data);
    }
    public function store_pesanan(Request $request)
    {

        $no_pesanan = (new GlobalController)->nomor_pesanan();
        // return response()->json($no_pesanan);
        $data_pesanan = Data_Pesanan::where('pesanan_id', $no_pesanan)->first();

        if ($data_pesanan) {
            return response()->json('failed');
        } else {
            $admin = Auth::user()->id;
            $pesanan_paketid = $request->pesanan_paketid;
            $pesanan_site = $request->pesanan_site;
            $pesanan_mitra = $request->pesanan_mitra;
            $pesanan_outlet = $request->pesanan_outlet;
            $pesanan_router = $request->pesanan_router;
            $pesanan_adminid = $request->pesanan_adminid;
            $pesanan_jumlah = $request->pesanan_jumlah;
            $pesanan_harga = $request->pesanan_harga;
            $pesanan_total_hpp = $request->pesanan_total_hpp;
            $pesanan_komisi = $request->pesanan_komisi;
            $pesanan_total_komisi = $request->pesanan_total_komisi;
            // return response()->json($request->all());
            for ($x = 0; $x < count($pesanan_paketid); $x++) {
                // $id_vhc = (new GlobalController)->id_vhc();
                Data_Pesanan::create([
                    'pesanan_id' => $no_pesanan,
                    // 'pesanan_voucherid'=> $id_vhc,
                    'pesanan_siteid' => $pesanan_site,
                    'pesanan_paketid' => $pesanan_paketid[$x],
                    'pesanan_mitraid' => $pesanan_mitra,
                    'pesanan_outletid' => $pesanan_outlet,
                    'pesanan_routerid' => $pesanan_router,
                    'pesanan_jumlah' => $pesanan_jumlah[$x],
                    'pesanan_hpp' => $pesanan_harga[$x],
                    'pesanan_komisi' => $pesanan_komisi[$x],
                    'pesanan_total_hpp' => $pesanan_total_hpp[$x],
                    'pesanan_total_komisi' => $pesanan_total_komisi[$x],
                    'pesanan_admin' => $admin,
                    'pesanan_tanggal' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                    'pesanan_status' => 'Proses',
                    'pesanan_status_generate' => '0',
                ]);
            }
            return response()->json('success');
        }
    }
    public function data_outlet()
    {
        $data['data_outlet'] = Data_Outlet::where('outlet_status', 'Enable')->get();
        $data['data_mitra'] = MitraSetting::join('users', 'users.id', '=', 'mitra_settings.mts_user_id')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.role_id', '14')
            ->where('users.status_user', 'Enable')
            ->get();

        // dd($data['data_mitra']);
        return view('hotspot/data_outlet', $data);
    }
    public function data_pesanan(Request $request)
    {
        // if()
        // Data_Pesanan::where('pesanan_status','proses')->update([
        //     'pesanan_status_generate' => 0,
        // ]);
        // dd('test');

        // dd(date('d-m-Y H:i:s', strtotime('apr/25/2025 20:31:28')));

        // Data_Voucher::where('vhc_paket',38)->update([
        //     'vhc_hjk' => '3000',
        // ]);
        // dd('test');
        $tanggal = Carbon::now();
        $data['status'] = $request->query('status');
        $data['outlet'] = $request->query('outlet');
        $data['bulan'] = $request->query('bulan');
        $data['q'] = $request->query('q');
        // dd($data['bulan']);
        if ($data['bulan']) {
            $startdate = date('Y-m-25', strtotime(Carbon::create($data['bulan'])->addMonth(-1)->toDateString()));
            $enddate = date('Y-m-25', strtotime($data['bulan']));
            // dd($startdate);
        } else {
            $startdate = date('Y-m-25', strtotime(Carbon::create($tanggal)->addMonth(-1)->toDateString()));
            $enddate = date('Y-m-24', strtotime($tanggal));
        }
        // Carbon::create($tanggal)->addMonth(1)->toDateString();
        $data['data_outlet'] = Data_Outlet::where('outlet_status', 'Enable')->get();
        $query = Data_Pesanan::orderBy('pesanan_tanggal', 'DESC')->orderBy('pesanan_status', 'ASC')->join('users', 'users.id', '=', 'data__pesanans.pesanan_mitraid')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__pesanans.pesanan_siteid')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->select('data__pesanans.pesanan_id', 'data__pesanans.pesanan_siteid', 'data__pesanans.pesanan_mitraid', 'data__pesanans.pesanan_outletid', 'data__pesanans.pesanan_tanggal', 'data__pesanans.pesanan_tgl_proses', 'data__pesanans.pesanan_tgl_bayar', 'data__pesanans.pesanan_status', 'data__pesanans.pesanan_status_generate', 'data__sites.site_nama', 'users.name', 'data__outlets.outlet_nama', DB::raw('sum(pesanan_total_hpp) as sumtotal_hpp'))
            ->groupBy('data__pesanans.pesanan_id', 'data__pesanans.pesanan_siteid', 'data__pesanans.pesanan_mitraid', 'data__pesanans.pesanan_outletid', 'data__pesanans.pesanan_tanggal', 'data__pesanans.pesanan_tgl_proses', 'data__pesanans.pesanan_tgl_bayar', 'data__pesanans.pesanan_status', 'data__pesanans.pesanan_status_generate', 'data__sites.site_nama', 'users.name', 'data__outlets.outlet_nama');
            // ->where(function ($query) use ($data) {
            //     $query->where('users.name', 'like', '%' . $data['q'] . '%');
            //     $query->where('data__outlets.outlet_nama', 'like', '%' . $data['q'] . '%');
            // });
        if ($data['bulan']) {
            $query->whereDate('pesanan_tanggal', '>=', $startdate)->whereDate('pesanan_tanggal', '<=', $enddate);
        }
        if ($data['outlet']) {
            $query->where('data__outlets.outlet_id', $data['outlet']);
        }
        // dd($data['q']);
        $data['data_pesanan'] = $query->paginate(10);

        #PERIODE BULAN BERJALAN
        $query1 = Data_Pesanan::where('pesanan_status', 'UNPAID')
            ->whereDate('pesanan_tanggal', '>=', $startdate)
            ->whereDate('pesanan_tanggal', '<=', $enddate);
        $data['UNPAID'] = $query1->sum('pesanan_total_hpp');

        $query2 = Data_Pesanan::where('pesanan_status', 'PAID')
            ->whereDate('pesanan_tanggal', '>=', $startdate)
            ->whereDate('pesanan_tanggal', '<=', $enddate);
        $data['PAID'] = $query2->sum('pesanan_total_hpp');

        #ALL PERIODE
        $query3 = Data_Pesanan::where('pesanan_status', 'UNPAID');
        $data['ALL_UNPAID'] = $query3->sum('pesanan_total_hpp');

        $query4 = Data_Pesanan::where('pesanan_status', 'PAID');
        $data['ALL_PAID'] = $query4->sum('pesanan_total_hpp');

        $data['periode'] = date('d-m-Y', strtotime($startdate)) . ' - ' . date('d-m-Y', strtotime($enddate));




        // dd($data['data_mitra']);
        return view('hotspot/data_pesanan', $data);
    }
    public function rincian_pesanan($id)
    {
        // dd($id);
        $data['rincian'] = Data_Pesanan::join('users', 'users.id', '=', 'data__pesanans.pesanan_mitraid')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__pesanans.pesanan_siteid')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->where('pesanan_id', $id)
            ->first();
        $data['deskripsi_pesanan'] = Data_Pesanan::orderBy('pesanan_status', 'DESC')
            ->join('pakets', 'pakets.paket_id', '=', 'data__pesanans.pesanan_paketid')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->select('data__pesanans.pesanan_id', 'pakets.paket_nama', 'data__pesanans.pesanan_jumlah', 'data__pesanans.pesanan_status', 'data__pesanans.pesanan_status_generate', 'data__pesanans.pesanan_hpp', 'data__pesanans.pesanan_total_hpp', 'data__pesanans.pesanan_paketid')
            ->where('pesanan_id', $id)
            ->get();
        $data['total'] = Data_Pesanan::where('pesanan_id', $id)->sum('pesanan_total_hpp');

        // dd($data['data_mitra']);
        return view('hotspot/rincian_pesanan', $data);
    }

    public function proses_pesanan(Request $request,)
    {
        $saldo = (new globalController)->total_mutasi($request->pesanan_mitraid);

        $total = $saldo - $request->pesanan_total; #SALDO MUTASI = DEBET - KREDIT

        $pesanan =  Data_Pesanan::where('pesanan_id', $request->pesanan_id)
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->first();


        $mutasi['mt_admin'] = $pesanan->pesanan_mitraid;
        $mutasi['mt_mts_id'] = $pesanan->pesanan_mitraid;
        $mutasi['mt_kategori'] = 'VOUCHER';
        $mutasi['mt_deskripsi'] = 'Pembelian Voucher Outlet ' . $pesanan->outlet_nama;
        $mutasi['mt_debet'] = $request->pesanan_total;
        $mutasi['mt_kredit'] = '0';
        $mutasi['mt_saldo'] = $total;
        $mutasi['mt_biaya_adm'] = 0;
        $mutasi['mt_cabar'] = '2';
        // dd($mutasi);
        Mutasi::create($mutasi);
        Data_Pesanan::where('pesanan_id', $request->pesanan_id)->update([
            'pesanan_status' => 'UNPAID',
            'pesanan_tgl_proses' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
        ]);
        $notifikasi = array(
            'pesan' => 'Berhasil proses pesanan',
            'alert' => 'success',
        );
        return redirect()->route('admin.vhc.rincian_pesanan', ['id' => $request->pesanan_id])->with($notifikasi);
    }

    public function store_vhc(Request $request)
    {
        // dd($request->pesanan_id.'-'.$request->paket_id);
        $id_user = Auth::user()->id;
        $id_vhc = (new GlobalController)->id_vhc();
        $pool = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';

        $pesanan = Data_Pesanan::where('pesanan_id', $request->pesanan_id)->where('pesanan_paketid', $request->paket_id)->first();
        // dd($pesanan);

        for ($x = 0; $x < $pesanan->pesanan_jumlah; $x++) {
            $randN = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);
            $data[] = [
                'vhc_id' =>  $id_vhc,
                'vhc_pesananid' =>  $pesanan->pesanan_id,
                'vhc_username' =>  $randN,
                'vhc_password' =>  $randN,
                'vhc_admin' =>  $pesanan->pesanan_admin,
                'vhc_site' =>  1,
                'vhc_status' =>  'Enable',
                'vhc_tgl_cetak' =>  date('Y-m-d h:i:s', strtotime(Carbon::now())),
                'vhc_router' =>  $pesanan->pesanan_routerid,
                'vhc_mitra' =>  $pesanan->pesanan_mitraid,
                'vhc_outlet' =>  $pesanan->pesanan_outletid,
                'vhc_paket' =>  $pesanan->pesanan_paketid,
                'vhc_hpp' =>  $pesanan->pesanan_hpp,
                'vhc_hjk' =>  $pesanan->pesanan_hpp + $pesanan->pesanan_komisi,
                'vhc_komisi' =>  $pesanan->pesanan_komisi,
                'vhc_status_pakai' =>  'Belum Terpakai',
            ];
        }
        //   dd($data);
        Data_Voucher::insert($data);

        $api = (new HotspotAPIController)->store_vhc($request->pesanan_id, $request->paket_id);
        if ($api == 'notif1') { #Berhasil Genaerate Voucher
            Data_Pesanan::where('pesanan_id', $pesanan->pesanan_id)->where('pesanan_paketid', $pesanan->pesanan_paketid)->update([
                'pesanan_status_generate' => '1',
                'pesanan_status' => 'UNPAID',
            ]);
            $notifikasi = array(
                'pesan' => 'Berhasil Generate Voucher',
                'alert' => 'success',
            );
        } elseif ($api == 'notif2') { #Paket tidak ada pada router
            $notifikasi = array(
                'pesan' => 'Paket belum tersedia pada Router',
                'alert' => 'error',
            );
            Data_Voucher::where('vhc_id', $id_vhc)->delete();
        } elseif ($api == 'notif3') { #Router Disconnect
            $notifikasi = array(
                'pesan' => 'Router Disconect',
                'alert' => 'error',
            );
            Data_Voucher::where('vhc_id', $id_vhc)->delete();
        }

        return redirect()->route('admin.vhc.rincian_pesanan', ['id' => $pesanan->pesanan_id])->with($notifikasi);
    }
    public function bayar_pesanan(Request $request, $id)
    {
        $id_user = Auth::user()->id;
        $bh_id = (new GlobalController)->nomor_bagihasil();
        $data_pesanan = Data_Pesanan::where('pesanan_id', $id)->first();
        $bagihasil['bh_id'] = $bh_id;
        $bagihasil['bh_mitraid'] = $data_pesanan->pesanan_mitraid;
        $bagihasil['bh_keterangan'] = 'Pelunasan Voucher Outlet ' . $request->outlet_nama;
        $bagihasil['bh_saldo'] = $request->jumlah_bayar;
        $bagihasil['bh_persen'] = $request->bh_persen;
        $bagihasil['bh_total_persentase'] = ($request->bh_persen / 100) * $request->jumlah_bayar;
        $bagihasil['bh_status'] = 'Lunas';
        $bagihasil['bh_admin'] = $id_user;

        #CEK SALDO MUTASI BILLER
        $saldo = (new GlobalController)->total_mutasi($data_pesanan->pesanan_mitraid); #SALDO MUTASI = DEBET - KREDIT
        $total = $saldo + $request->jumlah_bayar;



        $data['mt_mts_id'] = $data_pesanan->pesanan_mitraid;
        $data['mt_admin'] = $id_user;
        $data['mt_kategori'] = 'TOPUP';
        $data['mt_deskripsi'] = 'Pelunasan Voucher Outlet ' . $request->outlet_nama;
        $data['mt_kredit'] = $request->jumlah_bayar;
        $data['mt_saldo'] = $total;
        $data['mt_cabar'] = 2;

        Data_Pesanan::where('pesanan_id', $data_pesanan->pesanan_id)->update([
            'pesanan_status' => 'PAID',
            'pesanan_tgl_bayar' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
        ]);

        Mutasi::create($data); #INSERT LAPORAN TOPUP KE TABLE MUTASI
        Data_Bagihasil::create($bagihasil);

        $notifikasi = array(
            'pesan' => 'Pembayaran Berhasil',
            'alert' => 'success',
        );

        return redirect()->route('admin.vhc.rincian_pesanan', ['id' => $data_pesanan->pesanan_id])->with($notifikasi);
    }

    public function update_data_voucher()
    {
        // dd('RIFKI');
        $data['getuser'] = (new HotspotAPIController)->cek_data_update_voucher();
        foreach ($data['getuser'] as $us) {
            $comm =  explode("-", $us['comment']);
            // echo  $us['comment'].'<br>';
            // echo  $comm[0].'<br>';
            if ($comm[0] !=  'vc') {

                $query = Router::where('id', 17)->first();

                $ip =   $query->router_ip . ':' . $query->router_port_api;
                $user = $query->router_username;
                $pass = $query->router_password;
                $API = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($ip, $user, $pass)) {
                    $secret = $API->comm('/ip/hotspot/user/print', [
                        '?comment' => $comm[0],
                    ]);
                    foreach ($secret as $key) {
                        echo $key['name'];
                    }
                }

                //    $cari_comment = (new HotspotAPIController)->update_data_voucher($comm[0]);
                // echo  $comm[0].'<br>';
            }
            // $data['getNat'] = $API->comm('/ip/firewal/nat/print', [
            //     '?comment' => 'REMOTE_ONT',
            // ]);

        }
    }
    public function store_outlet(Request $request)
    {
        Session::flash('outlet_id', $request->outlet_id);
        Session::flash('outlet_nama', $request->outlet_nama);
        Session::flash('outlet_pemilik', $request->outlet_pemilik);
        Session::flash('outlet_hp', $request->outlet_hp);
        Session::flash('outlet_mitra', $request->outlet_mitra);
        Session::flash('outlet_alamat', $request->outlet_alamat);
        Session::flash('outlet_tgl_gabung', $request->outlet_tgl_gabung);
        Session::flash('outlet_status', $request->outlet_status);
        //  dd($request->outlet_nama);

        $request->validate([
            'outlet_id' => 'unique:data__outlets,outlet_id',
            'outlet_nama' => 'required',
            'outlet_pemilik' => 'required',
            'outlet_hp' => 'required',
            'outlet_mitra' => 'required',
            'outlet_alamat' => 'required',
            'outlet_tgl_gabung' => 'required',
            'outlet_status' => 'required',
        ], [
            'outlet_id.unique' => 'No layanan sudah ada, Ulangi Kembali',
            'outlet_nama.required' => 'Nama Outlet tidak boleh kosong',
            'outlet_pemilik.required' => 'Nama Pemilik tidak boleh kosong',
            'outlet_hp.required' => 'No Whatsapp tidak boleh kosong',
            'outlet_mitra.required' => 'Mitra tidak boleh kosong',
            'outlet_alamat.required' => 'Alamat tidak boleh kosong',
            'outlet_tgl_gabung.required' => 'Tanggal Gabung tidak boleh kosong',
            'outlet_status.required' => 'Status Mitra tidak boleh kosong',
        ]);
        $id_outlet = (new GlobalController)->id_outlet();
        $data['outlet_id'] = $id_outlet;
        $data['outlet_nama'] = $request->outlet_nama;
        $data['outlet_pemilik'] = $request->outlet_pemilik;
        $data['outlet_hp'] = $request->outlet_hp;
        $data['outlet_mitra'] = $request->outlet_mitra;
        $data['outlet_alamat'] = $request->outlet_alamat;
        $data['outlet_tgl_gabung'] = $request->outlet_tgl_gabung;
        $data['outlet_status'] = $request->outlet_status;
        // dd($id_outlet);
        Data_Outlet::create($data);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Outlet',
            'alert' => 'success',
        );
        return redirect()->route('admin.vhc.data_outlet')->with($notifikasi);
    }
    public function print_voucher($id)
    {
        // dd($id);
        $data['data_voucher'] = Data_Voucher::join('pakets', 'pakets.paket_id', '=', 'data__vouchers.vhc_paket')
            ->join('users', 'users.id', '=', 'data__vouchers.vhc_mitra')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__vouchers.vhc_outlet')
            ->where('vhc_pesananid', $id)->get();
        return view('hotspot/print_voucher', $data);
    }

    public function getPaketHotspot(Request $request, $id)
    {
        // $data['data_biaya'] = SettingBiaya::first();
        $data['data_paket'] = Paket::where("paket_id", $id)->where("paket_layanan", 'HOTSPOT')->get();
        return response()->json($data);
    }
    public function voucher_terjual()
    {
        $data = (new HotspotAPIController)->list_voucher_terjual();

        foreach ($data['getData'] as $value) {
            // dd($value['name']);
            $getname = explode("-|-", $value['name']);


            $date_mik = explode("/", $getname[0]);
            if ($date_mik[0] == 'jan') {
                $tgl_pakai = date($date_mik[2] . '-01-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'feb') {
                $tgl_pakai = date($date_mik[2] . '-02-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'mar') {
                $tgl_pakai = date($date_mik[2] . '-03-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'apr') {
                $tgl_pakai = date($date_mik[2] . '-04-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'may') {
                $tgl_pakai = date($date_mik[2] . '-05-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'jun') {
                $tgl_pakai = date($date_mik[2] . '-06-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'jul') {
                $tgl_pakai = date($date_mik[2] . '-07-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'aug') {
                $tgl_pakai = date($date_mik[2] . '-08-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'sep') {
                $tgl_pakai = date($date_mik[2] . '-09-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'oct') {
                $tgl_pakai = date($date_mik[2] . '-10-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'nov') {
                $tgl_pakai = date($date_mik[2] . '-11-' . $date_mik[1]);
            } elseif ($date_mik[0] == 'dec') {
                $tgl_pakai = date($date_mik[2] . '-12-' . $date_mik[1]);
            }
            $date_exp = explode("/", substr($getname[4], 0, 11));
            $time_exp = substr($getname[4], 12, 20);
            if ($date_exp[0] == 'jan') {
                $tgl_exp = date($date_exp[2] . '-01-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'feb') {
                $tgl_exp = date($date_exp[2] . '-02-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'mar') {
                $tgl_exp = date($date_exp[2] . '-03-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'apr') {
                $tgl_exp = date($date_exp[2] . '-04-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'may') {
                $tgl_exp = date($date_exp[2] . '-05-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'jun') {
                $tgl_exp = date($date_exp[2] . '-06-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'jul') {
                $tgl_exp = date($date_exp[2] . '-07-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'aug') {
                $tgl_exp = date($date_exp[2] . '-08-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'sep') {
                $tgl_exp = date($date_exp[2] . '-09-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'oct') {
                $tgl_exp = date($date_exp[2] . '-10-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'nov') {
                $tgl_exp = date($date_exp[2] . '-11-' . $date_exp[1]);
            } elseif ($date_exp[0] == 'dec') {
                $tgl_exp = date($date_exp[2] . '-12-' . $date_exp[1]);
            }


            Data_Voucher::where('vhc_username', $getname[2])->update([
                'vhc_tgl_jual' => $tgl_pakai . ' ' . $getname[1],
                'vhc_mac' =>  $getname[3],
                'vhc_status_pakai' => 'Terpakai',
                'vhc_exp' => $tgl_exp . ' ' . $time_exp,
                'vhc_script' => $value['name'],
            ]);
        }
        return redirect()->route('admin.vhc.data_voucher_terjual');
    }

    public function detail_voucher_terjual($id)
    {
        // return response()->json($id);
        $data_api = (new HotspotAPIController)->detail_voucherapi($id);
        return response()->json($data_api);
    }

    public function kick_voucher($username)
    {
        // dd($username);
        $data_api = (new HotspotAPIController)->kick_voucherapi($username);
        $notifikasi = array(
            'pesan' => 'Berhasil Kick User',
            'alert' => 'success',
        );
        return redirect()->route('admin.vhc.data_voucher_terjual')->with($notifikasi);
    }
    public function reset_voucher($username)
    {
        // dd($username);
        $data_api = (new HotspotAPIController)->reset_voucherapi($username);
        $notifikasi = array(
            'pesan' => 'Berhasil Kick User',
            'alert' => 'success',
        );
        return redirect()->route('admin.vhc.data_voucher_terjual')->with($notifikasi);
    }
    public function print_nota_pesanan($id)
    {
        dd($id);
        $data['rincian'] = Data_Pesanan::join('users', 'users.id', '=', 'data__pesanans.pesanan_mitraid')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__pesanans.pesanan_siteid')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->where('pesanan_id', $id)
            ->first();
        $data['deskripsi_pesanan'] = Data_Pesanan::orderBy('pesanan_status', 'DESC')
            ->join('pakets', 'pakets.paket_id', '=', 'data__pesanans.pesanan_paketid')
            ->join('data__outlets', 'data__outlets.outlet_id', '=', 'data__pesanans.pesanan_outletid')
            ->select('data__pesanans.pesanan_id', 'pakets.paket_nama', 'data__pesanans.pesanan_jumlah', 'data__pesanans.pesanan_status', 'data__pesanans.pesanan_status_generate', 'data__pesanans.pesanan_hpp', 'data__pesanans.pesanan_total_hpp', 'data__pesanans.pesanan_paketid')
            ->where('pesanan_id', $id)
            ->get();
        $data['total'] = Data_Pesanan::where('pesanan_id', $id)->sum('pesanan_total_hpp');

        // dd($data['data_mitra']);
        return view('hotspot/print_nota_pesanan', $data);
    }

    public function update_voucher()
    {
        $data = (new HotspotAPIController)->store_updatevhc();
        dd($data);
    }
}
