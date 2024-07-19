<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Models\PSB\InputData;
use App\Models\Tiket\SubTiket;
use App\Models\Tiket\Tiket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiketController extends Controller
{
    public function index()
    {
        $data['tiket'] = Tiket::join('users', 'users.id', '=', 'tikets.tiket_admin')->get();
        $data['input_data'] = InputData::all();

        return view('tiket/index', $data);
    }

    public function pilih_pelanggan($id)
    {
        $data['data_pelanggan'] =  InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->where('input_data.id', $id)
            ->first();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data['admin_user'] = Auth::user()->id;
        $data['data_pelanggan'] =  InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->where('registrasis.reg_nolayanan', '=', $request->tiket_nolayanan)
            ->first();
        $tiket_id = 'T' . rand(10000, 19999);
        $data['tiket_id'] = $tiket_id;
        $data['tiket_whatsapp'] = $data['data_pelanggan']->input_hp;
        $data['tiket_admin'] = $data['admin_user'];
        $data['tiket_status'] = 'NEW';

        $data['tiket_departemen'] = $request->tiket_departemen;
        $data['tiket_idpel'] = $request->tiket_idpel;
        $data['tiket_nolayanan'] = $request->tiket_nolayanan;
        $data['tiket_pelanggan'] = $request->tiket_pelanggan;
        $data['tiket_judul'] = $request->tiket_judul;
        $data['tiket_prioritas'] = $request->tiket_prioritas;
        $data['tiket_deskripsi'] = $request->tiket_deskripsi;
        $datas['subtiket_id'] = $tiket_id;
        $datas['subtiket_admin'] = $data['admin_user'];
        $datas['subtiket_status'] = 'NEW';
        $datas['subtiket_deskripsi'] = 'Membuat tiket dengan nomor' . $tiket_id;
        Tiket::create($data);
        SubTiket::create($datas);
        $notifikasi = [
            'pesan' => 'Berhasil Membuat Tiket',
            'alert' => 'success',
        ];
        return redirect()->route('admin.tiket.index')->with($notifikasi);
    }

    public function details($id)
    {
        $data['tiket'] = Tiket::select('tikets.*', 'tikets.created_at as tgl_buat')->where('tiket_id', $id)->first();
        $data['subtiket'] = SubTiket::join('users', 'users.id', '=', 'subtiket_admin')->select('users.*', 'sub_tikets.*', 'sub_tikets.created_at as tgl_progres')->where('subtiket_id', $id)->get();

        // dd($data);
        return view('tiket/details_tiket', $data);
    }
}
