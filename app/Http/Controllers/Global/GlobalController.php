<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Mitra\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Jurnal;
use App\Models\Transaksi\Kendaraan;
use Carbon\Carbon;
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
    public function mutasi_jurnal()
    {
        $data['debet'] = Jurnal::where('jurnal_status', 1)->sum('jurnal_debet');
        $data['kredit'] = Jurnal::where('jurnal_status', 1)->sum('jurnal_kredit');
        $data['saldo'] = $data['kredit'] - $data['debet'];

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

    function getRomawi($bln)
    {

        switch ($bln) {

            case 1:

                return "I";

                break;

            case 2:

                return "II";

                break;

            case 3:

                return "III";

                break;

            case 4:

                return "IV";

                break;

            case 5:

                return "V";

                break;

            case 6:

                return "VI";

                break;

            case 7:

                return "VII";

                break;

            case 8:

                return "VIII";

                break;

            case 9:

                return "IX";

                break;

            case 10:

                return "X";

                break;

            case 11:

                return "XI";

                break;

            case 12:

                return "XII";

                break;
        }
    }
    function no_inv()
    {
        $inv_tgl = Carbon::now();
        $bln = $inv_tgl->format('m');
        $th = $inv_tgl->format('y');


        // dd($bulanRom);
        $latest = Invoice::latest()->first();
        if (! $latest) {
            return $bln . '0001';
        }

        $string = substr($latest->inv_id, 2);
        // dd($bln . sprintf('%04d', $string + 1));    
        return $bln . sprintf('%04d', $string + 1);
    }

    function data_kendaraan()
    {
        $data = Kendaraan::select('kendaraans.*', 'kendaraans.id as trans_id', 'permissions.*')
            ->join('permissions', 'permissions.id', '=', 'kendaraans.trans_divisi_id');
        return $data;
    }
}
