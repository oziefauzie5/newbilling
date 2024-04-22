<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['nama'] = Auth::guard('pelanggan')->user()->nama;
        $data['hp'] = Auth::guard('pelanggan')->user()->hp;

        // $data['tagihan'] = Invoice::where('inv_idpel', $idpel)->where('inv_status', 'UNPAID')->get();
        // $data['rincian'] = Invoice::select('input_data.*', 'registrasis.created_at AS tgl_daftar', 'registrasis.*', 'invoices.*')
        //     ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
        //     ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        //     ->where('invoices.inv_status', '=', 'UNPAID')
        //     ->where('invoices.inv_idpel', '=', $idpel)
        //     ->get();
        $data['layanan'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->first();
        // $query = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
        //     ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        //     ->where('invoices.inv_idpel', '=', $idpel);

        // $data['details_layanan'] = $query->get();
        // $data['details'] =  $query->where('invoices.inv_status', '=', 'UNPAID')->get();

        // dd($idpel);
        // dd($data['tagihan']);


        return view('client/index', $data);
    }

    public function tagihan($inv_id)
    {

        $idpel = Auth::guard('pelanggan')->user()->id;
        // $data['nama'] = Auth::guard('pelanggan')->user()->nama;
        // $data['total']=SubInvoice::where('subinvoice_total', $id)->where('lh_status',0)->sum('lh_kredit');
        // $data['hp'] = Auth::guard('pelanggan')->user()->hp;
        $data['tagihan'] = Invoice::where('upd_idpel', $idpel)->where('upd_status', 'UNPAID')->get();
        $data['layanan'] = Invoice::join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
            ->join('pelanggans', 'pelanggans.idpel', '=', 'unpaids.upd_idpel')
            ->where('unpaids.upd_status', '=', 'UNPAID')
            ->where('unpaids.upd_idpel', '=', $idpel)
            ->get();
        $data['rincian'] = SubInvoice::where('subinvoice_id', $inv_id)->get();
        // $data['channels'] = (new TripayController)->getPaymentChannels();

        return view('client_area/tagihan', $data);
    }
}
