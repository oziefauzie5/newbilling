<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Mitra\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
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


    // public function laporan_harian($id)
    // {


    //     return $saldo;
    // }
    public function total_mutasi($id)
    {
        $debet = Mutasi::where('mt_mts_id', $id)->sum('mt_debet');
        $kredit = Mutasi::where('mt_mts_id', $id)->sum('mt_kredit');
        $saldo = $kredit - $debet;

        return $saldo;
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
            ->latest('inv_tgl_jatuh_tempo')
            ->first();
        return $data_tagihan;
    }
    public function al()
    {
        $wa_status =  SettingWhatsapp::first();
        return $wa_status;
    }
}
