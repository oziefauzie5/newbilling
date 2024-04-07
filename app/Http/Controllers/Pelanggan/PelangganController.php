<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['nama'] = Auth::guard('pelanggan')->user()->nama;
        $data['hp'] = Auth::guard('pelanggan')->user()->hp;

        $data['tagihan'] = Invoice::where('inv_idpel', $idpel)->where('inv_status', 'UNPAID')->get();
        $data['rincian'] = Invoice::select('input_data.*', 'registrasis.created_at AS tgl_daftar', 'registrasis.*', 'invoices.*')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_status', '=', 'UNPAID')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->get();
        $data['layanan'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->first();
        $query = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_idpel', '=', $idpel);

        $data['details_layanan'] = $query->get();
        $data['details'] =  $query->where('invoices.inv_status', '=', 'UNPAID')->get();

        // dd($idpel);
        // dd($data['tagihan']);


        return view('client/index', $data);
    }
}
