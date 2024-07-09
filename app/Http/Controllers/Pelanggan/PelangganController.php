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

class PelangganController extends Controller
{
    public function index()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['nama'] = Auth::guard('pelanggan')->user()->input_nama;
        // $data['hp'] = Auth::guard('pelanggan')->user()->hp;

        // $data['tagihan'] = Invoice::where('inv_idpel', $idpel)->where('inv_status', 'UNPAID')->get();
        $data['tagihan'] = Invoice::where('invoices.inv_idpel', '=', $idpel)
            ->paginate(3);
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

    public function details()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['details'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_idpel', $idpel)->first();
        return view('client/details', $data);
    }

    public function tagihan($inv_id)
    {

        $data['channels'] = (new TripayController)->getPaymentChannels();
        $idpel = Auth::guard('pelanggan')->user()->id;
        // $data['nama'] = Auth::guard('pelanggan')->user()->nama;
        // $data['total']=SubInvoice::where('subinvoice_total', $id)->where('lh_status',0)->sum('lh_kredit');
        // $data['tagihan'] = Invoice::where('inv_idpel', $idpel)->where('inv_status', 'UNPAID')->get();
        $data['layanan'] = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('invoices.inv_id', '=', $inv_id)
            ->where('invoices.inv_status', '!=', 'PAID')
            ->where('invoices.inv_idpel', '=', $idpel)
            ->first();

        $data['subinvoice'] = SubInvoice::where('subinvoice_id', $data['layanan']->inv_id)->get();
        // $data['rincian'] = SubInvoice::where('subinvoice_id', $inv_id)->get();
        // $data['channels'] = (new TripayController)->getPaymentChannels();

        // dd($data['channels']);

        return view('client/tagihan', $data);
    }

    public function payment_tripay(Request $request)
    {
        $inv = $request->inv;
        $method = $request->code;
        $icon = $request->icon;
        // $datapel = (new globalController)->data_transaksiPelanggan($inv);

        $tripay = (new TripayController)->requestTransaksi($method, $inv, $icon);

        $res = json_decode($tripay);
        if ($res->success == false) {
            $notifikasi = array(
                'pesan' => $res->message,
                'alert' => 'error',
            );
            return redirect()->route('client.index')->with($notifikasi);
        } else {
            $response = json_decode($tripay)->data;
            // dd($response->reference);
            return redirect()->route('client.show', ['refrensi' => $response->reference, 'inv_id' => $inv]);
        }
    }

    public function show(Request $request, $refrensi, $inv)
    {
        dd($inv . ' - - ' . $refrensi);

        $tripay = (new TripayController)->detailsTransakasi($refrensi);
        // dd($tripay);
        $date = Carbon::parse($tripay->expired_time);
        $today = Carbon::now()->isoFormat('D MMMM Y');
        $expire = $date->isoFormat('D MMMM Y H:m:s');
        return view('client/tagihanshow', compact('tripay', 'expire'));
    }
}
