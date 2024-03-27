<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $idpel = Auth::guard('pelanggan')->user()->id;
        $data['nama'] = Auth::guard('pelanggan')->user()->nama;
        $data['hp'] = Auth::guard('pelanggan')->user()->hp;

        // $data['tagihan'] = Unpaid::where('upd_idpel', $idpel)->where('upd_status', 'UNPAID')->get();
        // $data['rincian'] = Unpaid::select('registrasis.*', 'pelanggans.created_at AS tgl_daftar', 'pelanggans.*', 'unpaids.*')
        //     ->join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
        //     ->join('pelanggans', 'pelanggans.idpel', '=', 'unpaids.upd_idpel')
        //     ->where('unpaids.upd_status', '=', 'UNPAID')
        //     ->where('unpaids.upd_idpel', '=', $idpel)
        //     ->get();
        // $data['layanan'] = Unpaid::join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
        //     ->where('unpaids.upd_idpel', '=', $idpel)
        //     ->first();
        // $query = Unpaid::join('registrasis', 'registrasis.id', '=', 'unpaids.upd_idpel')
        //     ->join('pelanggans', 'pelanggans.idpel', '=', 'unpaids.upd_idpel')
        //     ->where('unpaids.upd_idpel', '=', $idpel);

        // $data['details_layanan'] = $query->get();
        // $data['details'] =  $query->where('unpaids.upd_status', '=', 'UNPAID')->get();

        // dd($idpel);
        // dd($data['tagihan']);

        return view('client_area/index', $data);
    }
}
