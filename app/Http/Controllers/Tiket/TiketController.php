<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\Tiket\SubTiket;
use App\Models\Tiket\Tiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiketController extends Controller
{
    public function index()
    {
        $data['tiket'] = Tiket::join('users', 'users.id', '=', 'tikets.tiket_admin')->get();
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();

        return view('tiket/index', $data);
    }

    public function pilih_pelanggan($id)
    {
        $data['data_pelanggan'] =  InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->where('registrasis.reg_idpel', $id)
            ->first();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $tanggal = date('d M Y H:m:s', strtotime(Carbon::now()));
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

        $pesan_group['ket'] = 'tiket';
        $pesan_group['status'] = '0';
        $pesan_group['target'] = '120363028776966861@g.us';
        $pesan_group['pesan'] = '               -- TIKET GANGGUAN --

Hallo Broo..  
Ada tiket masuk ke sistem nih! 😊

Notiket : *' . $tiket_id . '*
Topik : ' . $request->tiket_judul . '
Deskripsi : *' . $request->tiket_deskripsi . '*

Pelanggan : ' . $request->tiket_pelanggan . '
Alamat : ' . $data['data_pelanggan']->input_alamat_pasang . '
Tanggal tiket : ' . $tanggal . '

Mohon segera diproses dari aplikasi dan di tindak lanjuti ya.
Terima kasih.';

        $pesan_pelanggan['ket'] = 'tiket';
        $pesan_pelanggan['status'] = '0';
        $pesan_pelanggan['target'] = $data['data_pelanggan']->input_hp;
        $pesan_pelanggan['pesan'] = '               -- TIKET GANGGUAN --

Pelanggan yth
Tiket anda sudah masuk ke system kami.

Nomor tiket : *' . $tiket_id . '* 
Topik : ' . $request->tiket_judul . '
Deskripsi : ' . $request->tiket_deskripsi . '
Tanggal tiket : ' . $tanggal . '

Tiket Laporan anda akan kami proses secepat mungkin, pastikan nomor anda selalu aktif agar bisa di hubungi kembali.
Terima kasih.';

        // dd($pesan_pelanggan);
        Pesan::create($pesan_pelanggan);
        Pesan::create($pesan_group);
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
    public function pesan_team()
    {
        $pesan['status'] = 'tiket';
        // $pesan['hp'] = $tampildata->hp;

        $pesan['pesan'] = 'Hallo Broo..  
        Ada tiket masuk ke sistem nih! 😊
        Notiket : T487484
        Topik : Nama Internet Tidak Muncul
        Deskripsi : Nama Internet Tidak Muncul
        Pelanggan : IRVAN MAULANA
        Alamat : KEDUNG HALANG SERIKAT RT O1/01 (DEKAT RIDHO CELL)
        Tanggal tiket : 20 Juli 2024 07:47:58
        Mohon segera diproses dari aplikasi dan di tindak lanjuti ya.
        Terima kasih.';
    }
}
