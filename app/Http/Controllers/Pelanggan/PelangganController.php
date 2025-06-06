<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaksi\TripayController;
use App\Models\PSB\InputData;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PelangganController extends Controller
{
    public function maintenance()
    {
       
        return view('client/maintenance');
    }
    public function index()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['nama'] = Auth::guard('pelanggan')->user()->input_nama;

        $data['tagihan']  = Invoice::where('invoices.inv_idpel', '=', $idpel)
            ->where('corporate_id',Session::get('corp_id'))
            ->latest()
            ->paginate(2);
        $data['layanan'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->where('invoices.corporate_id',Session::get('corp_id'))
            ->first();
        $data['details'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.corporate_id',Session::get('corp_id'))
            ->where('registrasis.reg_idpel', $idpel)->first();
        return view('client/index', $data);
    }

    public function details()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['details'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('input_data.corporate_id',Session::get('corp_id'))
            ->where('registrasis.reg_idpel', $idpel)->first();
        return view('client/details', $data);
    }

    public function tagihan($inv_id)
    {

        $data['channels'] = (new TripayController)->getPaymentChannels();
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['layanan'] = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('invoices.corporate_id',Session::get('corp_id'))
            ->where('invoices.inv_id', '=', $inv_id)
            ->where('invoices.inv_status', '!=', 'PAID')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->first();

        $data['subinvoice'] = SubInvoice::where('subinvoice_id', $data['layanan']->inv_id)->get();
        return view('client/tagihan', $data);
    }

    public function payment_tripay(Request $request)
    {
        $inv = $request->inv;
        $method = $request->code;
        $icon = $request->icon;
        $data_inv = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
                            ->where('invoices.corporate_id',Session::get('corp_id'))
                            ->where('invoices.inv_id', $inv)
                            ->first();
        $tripay = (new TripayController)->requestTransaksi($method, $data_inv, $inv, $icon);


        $res = json_decode($tripay);
        if ($res->success == false) {
            $notifikasi = array(
                'pesan' => $res->message,
                'alert' => 'error',
            );
            return redirect()->route('client.index')->with($notifikasi);
        } else {
            $response = json_decode($tripay)->data;

            return redirect()->route('client.show', ['refrensi' => $response->reference]);
        }
    }

    public function show(Request $request, $refrensi)
    {
        // dd($refrensi);
        $tripay = (new TripayController)->detailsTransakasi($refrensi);
        // dd($tripay );
        $cek_inv = Invoice::where('inv_id', $tripay->merchant_ref)
                        ->where('corporate_id',Session::get('corp_id'))
                        ->first();
        if ($cek_inv->inv_status != 'PAID') {
            $date = Carbon::parse($tripay->expired_time);
            $today = Carbon::now()->isoFormat('D MMMM Y');
            $expire = $date->isoFormat('D MMMM Y H:m:s');
            return view('client/tagihanshow', compact('tripay', 'expire'));
        } else {
            return redirect()->route('client.index')->with('success', 'Terimakasih. Tagihan anda telah terbayar');
        }
    }
}
