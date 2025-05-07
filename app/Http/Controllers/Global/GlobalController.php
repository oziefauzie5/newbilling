<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi\Data_Site;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Mitra\Mutasi;
use App\Models\Mitra\MutasiSales;
use App\Models\PSB\InputData;
use App\Models\Teknisi\Data_Odc;
use App\Models\Teknisi\Data_Odp;
use App\Models\Teknisi\Data_Olt;
use App\Models\Teknisi\Data_pop;
use App\Models\Tiket\Data_Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Kendaraan;
use App\Models\Hotspot\Data_Outlet;
use App\Models\Hotspot\Data_Pesanan;
use App\Models\Hotspot\Data_Bagihasil;
use App\Models\Hotspot\Data_Voucher;
use App\Models\Router\Paket;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GlobalController extends Controller
{
    public function tanggal() #mennampilkan data user
    {
        $tanggal = Carbon::now();

        return $tanggal;
    }
    public function user_admin() #mennampilkan data user
    {
        $data['user_id'] = Auth::user()->id;
        $data['user_nama'] = Auth::user()->name;
        $data['user_hp'] = Auth::user()->hp;
        return $data;
    }
    public function setting_biaya() #mennampilkan data Setting Biaya
    {
        $data = SettingBiaya::first();
        return $data;
    }
    public function data_user($iduser) #mennampilkan data user sesuai hak akses (Mitra Controller |  |  |)
    {
        $data_user =  DB::table('users')
            ->select('users.name AS nama_user', 'roles.name', 'users.hp', 'users.email', 'users.alamat_lengkap', 'users.username', 'users.password')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', '=', $iduser)
            ->first();
        return $data_user;
    }
    public function role($iduser) #mennampilkan data user sesuai hak akses (Mitra Controller |  |  |)
    {
        $role =  DB::table('users')
            ->select('users.name AS nama_user', 'roles.name', 'roles.id as role_id', 'users.hp', 'users.email', 'users.alamat_lengkap', 'users.username', 'users.password')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', '=', $iduser)
            ->first();
        return $role;
    }
    public function getTeknisi() #mennampilkan data user sesuai hak akses (Mitra Controller |  |  |)
    {
        $teknisi =  DB::table('users')
            ->select('users.id as user_id', 'users.name AS user_nama', 'roles.name', 'roles.id as role_id', 'users.hp', 'users.email', 'users.alamat_lengkap', 'users.username', 'users.password')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.id', '=', '11')
            ->get();
        return $teknisi;
    }


    public function all_user() #mennampilkan data user
    {
        $all_user =  DB::table('users')
            ->select('users.name AS nama_user', 'roles.name', 'users.*', 'model_has_roles.*')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', '>', 10);
        return $all_user;
    }
    public function setting_akun() #mennampilkan data akun/bank
    {
        $setting_akun = SettingAkun::where('id', '>', 1);
        return $setting_akun;
    }
    public function no_invoice_mitra()
    {
        $count = Mutasi::count();
        if ($count == 0) {
            $count_invoice = 1;
        } else {
            $count_invoice = $count + 1;
        }
        // dd($count_invoice);
        $invoice = sprintf("%08d", $count_invoice);
        return $invoice;
    }

    public function total_mutasi($id)
    {
        $debet = Mutasi::where('mt_mts_id', $id)->sum('mt_debet');
        $kredit = Mutasi::where('mt_mts_id', $id)->sum('mt_kredit');
        $saldo = $kredit - $debet;

        return $saldo;
    }
    public function total_mutasi_sales($id)
    {
        $debet = MutasiSales::where('smt_user_id', $id)->sum('smt_debet');
        $kredit = MutasiSales::where('smt_user_id', $id)->sum('smt_kredit');
        // dd($kredit);
        $saldo = $kredit - $debet;

        return $saldo;
    }
    public function mutasi_jurnal()
    {
        $data['debet'] = Jurnal::where('jurnal_status',1)->sum('jurnal_debet');
        $data['kredit'] = Jurnal::where('jurnal_status',1)->sum('jurnal_kredit');
        $data['saldo'] = $data['kredit'] - $data['debet'];

        return $data;
    }
    public function mutasi_jurnal_reimburse($ktg, $jenis, $plat)
    {
        $month = date('m', strtotime(Carbon::now()));
        // dd($ktg);
        $data['debet'] = Jurnal::whereMonth('created_at', $month)->where('jurnal_kategori', $ktg)->where('jurnal_keterangan', $jenis)->where('jurnal_uraian', $plat)->sum('jurnal_debet');
        $data['kredit'] = Jurnal::whereMonth('created_at', $month)->where('jurnal_kategori', $ktg)->where('jurnal_keterangan', $jenis)->where('jurnal_uraian', $plat)->sum('jurnal_kredit');
        $data['saldo'] = $data['debet'] - $data['kredit'];

        return $data;
    }
    public function data_tagihan($invoice)
    {
        $data_tagihan =  DB::table('invoices')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_id', '=', $invoice)
            ->orWhere('inv_nolayanan', '=', $invoice)
            ->orWhere('input_data.input_hp', '=', $invoice)
            ->orWhere('input_data.input_nama', '=', $invoice)
            ->latest('inv_tgl_jatuh_tempo')
            ->first();
        return $data_tagihan;
    }
    public function whatsapp_status()
    {
        $wa_status =  SettingWhatsapp::first();
        return $wa_status;
    }

    function no_inv()
    {
        $inv_tgl = Carbon::now();
        $bln = $inv_tgl->format('m');
        $th = $inv_tgl->format('y');
        // $latest = Invoice::orderBy('created_at', 'DESC')->latest()->first();
        // if (! $latest) {
        //     return $bln . $th . '0001';
        // }
        // $string = substr($latest->inv_id, 2);
        // return $bln . sprintf('%04d', $string + 1);
        $latest = Invoice::whereMonth('inv_tgl_isolir','=', $bln)->count();
        if ($latest == 0) {
            return $bln . $th . '0001';
        }
            return $bln.$th . $latest + 1;
    }

    function idpel_()
    {
        $bl = date('m', strtotime(new Carbon()));
        $latest = InputData::orderBy('created_at', 'DESC')->latest()->first();
        $cek_idpel = InputData::whereId($latest->id)->count();
        if ($cek_idpel > 1) {
            if (! $latest->id) {
                return  '0001';
            } else {
                return sprintf('%04d', $latest->id + 2);
            }
        } else {
            if (! $latest->id) {
                return  '0001';
            } else {
                return sprintf('%04d', $latest->id + 1);
            }
        }
    }
    function id_outlet()
    {
        $bln = date('ym', strtotime(new Carbon()));
        $latest = Data_Outlet::orderBy('created_at', 'DESC')->latest()->first();
        if (! $latest) {
            return $bln . '0001';
        }
        $string = substr($latest->outlet_id, 4);
        return $bln . sprintf('%04d', $string + 1);
    }
    function id_vhc()
    {
        $bln = date('ym', strtotime(new Carbon()));
        $latest = Data_Voucher::orderBy('created_at', 'DESC')->latest()->first();
        if (! $latest) {
            return '01';
        }
        $string = substr($latest->vhc_id, 4);
        return sprintf('%02d', $string + 1);
    }

    function data_kendaraan()
    {
        $data = Kendaraan::select('kendaraans.*', 'kendaraans.id as trans_id', 'permissions.*')
            ->join('permissions', 'permissions.id', '=', 'kendaraans.trans_divisi_id');
        return $data;
    }

    public function getSite($id)
    {
        $kode_site = Data_Site::join('data_pops', 'data_pops.pop_id_site', '=', 'data__sites.site_id')
            ->where("site_id", $id)->get();
        return response()->json($kode_site);
    }
    public function getMitraSite($id) #Layanan Hotspot
    {
        $kode_site = User::join('data__sites', 'data__sites.site_id', '=', 'users.site')
        ->join('model_has_roles','model_has_roles.model_id','=','users.id')
        ->where('model_has_roles.role_id','14')
        ->where('users.status_user','Enable')
        ->where("data__sites.site_id", $id)
        ->get();
        return response()->json($kode_site);
    }
    public function getOutlet($id) #Layanan Hotspot
    {
        $get_outlet = Data_Outlet::where("outlet_mitra", $id)
        ->get();
        return response()->json($get_outlet);
    }
    public function getPaket($id) #Layanan Hotspot
    {
        // return response()->json($id);
        $getPaket = Paket::where('paket_id', $id)
        ->first();
        return response()->json($getPaket);
    }
    public function getOlt($id)
    {
        $kode_olt = Data_pop::join('data__olts', 'data__olts.olt_id_pop', '=', 'data_pops.pop_id')
            ->where("pop_id", $id)->get();
        return response()->json($kode_olt);
    }
    public function getPop($id)
    {
        $kode_pop = Data_pop::join('routers', 'routers.router_id_pop', '=', 'data_pops.pop_id')
            ->where("pop_id", $id)->where("pop_status", 'Enable')->get();
        return response()->json($kode_pop);
    }
    public function getOdc($id)
    {
        $kode_pop = Data_Odc::join('data__olts', 'data__olts.olt_id', '=', 'data__odcs.odc_id_olt')
            ->where("data__olts.olt_id", $id)->get();
        return response()->json($kode_pop);
    }

    public function validasi_odp($id)
    {
        $kode_pop = Data_Odp::join('data__odcs', 'data__odcs.odc_id', '=', 'data__odps.odp_id_odc')
            ->join('data__olts', 'data__olts.olt_id', '=', 'data__odcs.odc_id_olt')
            ->where("odp_kode", $id)->first();
        return response()->json($kode_pop);
    }

    public function valBarang($id)
    {
        $kode_barang = Data_Barang::where("barang_id", $id)->where("barang_lokasi", 1)->first();
        return response()->json($kode_barang);
    }

    public function no_surat_keterang()
    {
        $y = date('y');
        $m = date('m');
        $d = date('d');
        $latest = Data_BarangKeluar::orderBy('bk_id', 'DESC')->latest()->first();

        $cek_noskb = Data_BarangKeluar::where('bk_id', $latest->bk_id)->count();
        if ($cek_noskb > 1) {
            if (! $latest) {
                return 'SKB/' . $y . $m . $d . '/0001';
            }
            $string = substr($latest->bk_id, 11);
            $nosk  = 'SKB/' . $y . $m . $d  . '/' . sprintf('%04d', $string + 2);
            return $nosk;
        } else {
            if (! $latest) {
                return 'SKB/' . $y . $m . $d . '/0001';
            }
            $string = substr($latest->bk_id, 11);
            $nosk  = 'SKB/' . $y . $m . $d  . '/' . sprintf('%04d', $string + 1);
            return $nosk;
        }
    }
    public function nomor_tiket()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $y = date('y');
        $m = date('m');
        $d = date('d');
        ##-Membuat Nomor Tiket sesuai tanggal
        $latest = Data_Tiket::whereDate('created_at', $date)->latest()->first();
        if (! $latest) {
            return $y . $m . $d . '0001';
        }
        $string = substr($latest->tiket_id, 6);
        $no_tiket  = $y . $m . $d  . sprintf('%04d', $string + 1);

        return $no_tiket;
    }
    public function nomor_pesanan() #layanan Voucher
    {
        $bln = date('ym', strtotime(new Carbon()));
        $latest = Data_Pesanan::orderBy('created_at', 'DESC')->latest()->first();
        // return $latest;
        if (! $latest) {
            return $bln . '0001';
        }
        $string = substr($latest->pesanan_id, 4);
        return $bln . sprintf('%04d', $string + 1);
    }
    public function nomor_bagihasil() #layanan Voucher
    {
        $bln = date('ym', strtotime(new Carbon()));
        $latest = Data_Bagihasil::orderBy('created_at', 'DESC')->latest()->first();
        // return $latest;
        if (! $latest) {
            return $bln . '0001';
        }
        $string = substr($latest->bh_id, 4);
        return $bln . sprintf('%04d', $string + 1);
    }
}
