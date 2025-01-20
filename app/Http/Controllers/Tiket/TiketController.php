<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\Tiket\Data_SubTiket;
use App\Models\Tiket\Data_Tiket;
use App\Models\Tiket\SubTiket;
use App\Models\Tiket\Tiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TiketController extends Controller
{
    public function data_tiket(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Data_Tiket::join('users', 'users.id', '=', 'data__tikets.tiket_pembuat')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->orderBy('data__tikets.created_at', 'DESC', 'data__tikets.tiket_status', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('tiket_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_data.input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('registrasis.reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('tiket_status', 'like', '%' . $data['q'] . '%');
            });
        $data['tiket'] = $query->paginate(10);
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();

        return view('tiket/data_tiket', $data);
    }
    public function buat_tiket(Request $request)
    {
        $date = date('d M Y H:m:s', strtotime(Carbon::now()));
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();
        $count = Data_Tiket::whereDate('created_at', $date)->count();
        $y = date('y');
        $m = date('m');
        $d = date('d');
        if ($count == 0) {
            $string = '1';
        } else {
            $string = $count + 1;
        }
        $data['tiket_id'] = $y . $d . $m . sprintf('%03d', $string);

        return view('tiket/buat_tiket', $data);
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

        $pembuat = Auth::user()->id;
        $tiket['tiket_id'] = $request->tiket_id;
        $tiket['tiket_pembuat'] = $pembuat;
        $tiket['tiket_kode'] = 'T-' . $request->tiket_id;
        $tiket['tiket_idpel'] = $request->tiket_idpel;
        $tiket['tiket_jenis'] = $request->tiket_jenis;
        $tiket['tiket_nama'] = $request->tiket_nama;
        $tiket['tiket_waktu_kunjungan'] = $request->tiket_waktu_kunjungan;
        $tiket['tiket_keterangan'] = $request->tiket_keterangan;
        $tiket['tiket_status'] = 'NEW';
        // $tiket['tiket_waktu_penanganan'] = $request->tiket_waktu_penanganan;
        // $tiket['tiket_waktu_selesai'] = $request->tiket_waktu_selesai;
        // $tiket['tiket_foto'] = $request->tiket_foto;
        // $tiket['tiket_kendala'] = $request->tiket_kendala;
        // $tiket['tiket_tindakan'] = $request->tiket_tindakan;
        // $tiket['tiket_teknisi1'] = $request->tiketiket_teknisi1t_nama;
        // $tiket['tiket_teknisi2'] = $request->tiket_teknisi2;
        // $tiket['tiket_barang1'] = $request->tiket_barang1;
        // $tiket['tiket_barang2'] = $request->tiket_barang2;
        // $tiket['tiket_barang3'] = $request->tiket_barang3;



        $tanggal = date('d M Y H:m:s', strtotime(Carbon::now()));
        $data['data_pelanggan'] =  InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->where('registrasis.reg_idpel', '=', $request->tiket_idpel)
            ->first();


        $users_teknisi = User::select('model_has_roles.*', 'roles.*', 'users.*', 'users.name as nama_teknisi')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.id', '11')
            ->get();

        $maps = str_replace(" ", "+", $data['data_pelanggan']->input_koordinat);

        $status = (new GlobalController)->whatsapp_status();
        if ($status->wa_status == 'Enable') {
            $pesan_group['status'] = '0';
            $pesan_pelanggan['status'] = '0';
        } else {
            $pesan_group['status'] = '10';
            $pesan_pelanggan['status'] = '10';
        }




        foreach ($users_teknisi as $t) {

            if ($status->wa_status == 'Enable') {

                Pesan::create([
                    'ket' =>  'tiket',
                    'target' =>  $t->hp,
                    'status' =>  '0',
                    'nama' =>  $t->nama_teknisi,
                    'pesan' => '               -- TIKET GANGGUAN --

Hallo Broo ' . $t->nama_teknisi . '
Ada tiket masuk ke sistem nih! 😊

No. Tiket : *' . $tiket['tiket_kode'] . '*
Topik : ' . $request->tiket_nama . '
Keterangan : *' . $request->tiket_keterangan . '*
Tgl Kunjungan : *' . $request->tiket_waktu_kunjungan . '*

No. Layanan : ' . $data['data_pelanggan']->reg_nolayanan . '
Pelanggan : ' . $request->tiket_pelanggan . '
Alamat : ' . $data['data_pelanggan']->input_alamat_pasang . '
Maps : https://www.google.com/maps/place/' . $maps . '
Whatsapp : 0' . $data['data_pelanggan']->input_hp . '
Tanggal tiket : ' . $tanggal . '
'
                ]);
            } else {
                Pesan::create([
                    'ket' =>  'tiket',
                    'target' =>  $t->hp,
                    'status' =>  '10',
                    'nama' =>  $t->nama_teknisi,
                    'pesan' => '               -- TIKET GANGGUAN --

Hallo Broo ' . $t->nama_teknisi . '
Ada tiket masuk ke sistem nih! 😊

No. Tiket : *' . $tiket['tiket_kode'] . '*
Topik : ' . $request->tiket_nama . '
Keterangan : *' . $request->tiket_keterangan . '*
Tgl Kunjungan : *' . $request->tiket_waktu_kunjungan . '*

No. Layanan : ' . $data['data_pelanggan']->reg_nolayanan . '
Pelanggan : ' . $request->tiket_pelanggan . '
Alamat : ' . $data['data_pelanggan']->input_alamat_pasang . '
Maps : https://www.google.com/maps/place/' . $maps . '
Whatsapp : 0' . $data['data_pelanggan']->input_hp . '
Tanggal tiket : ' . $tanggal . '
'
                ]);
            }
        }

        $pesan_pelanggan['ket'] = 'tiket';
        $pesan_pelanggan['target'] = $data['data_pelanggan']->input_hp;
        $pesan_pelanggan['nama'] = $data['data_pelanggan']->input_nama;
        $pesan_pelanggan['pesan'] = '               -- TIKET GANGGUAN --

Pelanggan yth
Tiket anda sudah masuk ke system kami.

Nomor tiket : *' . $tiket['tiket_kode'] . '* 
Topik : ' . $request->tiket_nama . '
Keterangan : ' . $request->tiket_keterangan . '
Tanggal tiket : ' . $tanggal . '

Tiket Laporan anda akan kami proses secepat mungkin, pastikan nomor anda selalu aktif agar bisa di hubungi kembali.
Terima kasih.';

        // dd($pesan_pelanggan);
        Pesan::create($pesan_pelanggan);
        // Pesan::create($pesan_group);
        Data_Tiket::create($tiket);
        // dd($tiket);
        $notifikasi = [
            'pesan' => 'Berhasil Membuat Tiket',
            'alert' => 'success',
        ];
        return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
    }


    public function export(Request $request)
    {
        $data['start_date'] = $request->query('start_date');
        $data['end_date'] = $request->query('end_date');
        $data['tiket'] = Data_Tiket::select('tikets.*', 'tikets.created_at as tgl_buat')
            ->whereDate('tikets.created_at', '>=', date('Y-m-d', strtotime($data['start_date'])))
            ->whereDate('tikets.created_at', '<=', date('Y-m-d', strtotime($data['end_date'])))
            ->first();
        // echo '<table><tr><td>' . $data['tiket']->tiket_pelanggan . '</td></tr></table>';
        dd($data['tiket']->tiket_pelanggan);

        // dd($data);
        return view('tiket/details_tiket', $data);
    }
    public function details_tiket(Request $request)
    {
        $data['teknisi'] = (new GlobalController)->getTeknisi();
        // $data['start_date'] = $request->query('start_date');
        // $data['end_date'] = $request->query('end_date');
        $data['tiket'] = Data_Tiket::join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->select('data__tikets.*', 'input_data.*', 'data__tikets.created_at as tgl_buat')
            ->first();


        // dd($data);
        return view('tiket/details_tiket', $data);
    }

    public function tiket_update(Request $request, $id)
    {
        $datetime = date('Y-m-d h:m:s', strtotime(carbon::now()));
        $y = date('y');
        $m = date('m');
        $admin_closed = Auth::user()->id;
        $tiket['tiket_id'] = $request->tiket_id;
        $tiket['tiket_jenis'] = $request->tiket_jenis;
        $tiket['tiket_status'] = $request->tiket_status;
        $tiket['tiket_pending'] = $request->tiket_pending;
        $tiket['tiket_teknisi1'] = $request->tiket_teknisi1;
        $tiket['tiket_teknisi2'] = $request->tiket_teknisi2;

        if ($request->tiket_status == 'Closed') {
            $tiket['tiket_waktu_selesai'] = $datetime;
            $tiket['tiket_kendala'] = $request->tiket_kendala;
            $tiket['tiket_tindakan'] = $request->tiket_tindakan;
            $tiket['tiket_waktu_penanganan'] = $datetime;
            $tiket['tiket_waktu_selesai'] = $datetime;
            $tiket['tiket_barang'] = $request->tiket_nama_barang1 . ' - ' . $request->tiket_nama_barang2 . ' - Dropcore : ' . $request->tiket_total_kabel . 'M - ' . $request->tiket_nama_barang4;
            // $barang['tiket_before'] = $request->tiket_before;
            // $barang['tiket_after'] = $request->tiket_after;
            $barang['tiket_total_kabel'] = $request->tiket_total_kabel;

            $photo = $request->file('tiket_foto');
            $filename = $photo->getClientOriginalName();
            $path = 'laporan-tiket/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $tiket['tiket_foto'] = $filename;

            $data_barang = Data_Barang::whereIn('barang_id', [$request->tiket_barang1, $request->tiket_barang2, $request->tiket_barang3, $request->tiket_barang4])->get();
            foreach ($data_barang as $db) {
                Data_BarangKeluar::create([
                    'bk_id' => $y . $m . mt_rand(1000, 9999),
                    'bk_jenis_laporan' => 'Instalasi',
                    'bk_id_barang' => $db->barang_id,
                    'bk_id_tiket' => '0',
                    'bk_kategori' => $db->barang_kategori,
                    'bk_satuan' => $db->barang_satuan,
                    'bk_nama_barang' => $db->barang_nama,
                    'bk_model' => $db->barang_merek,
                    'bk_mac' => $db->barang_mac,
                    'bk_sn' => $db->barang_sn,
                    'bk_jumlah' => 1,
                    'bk_keperluan' => $request->tiket_jenis,
                    'bk_foto_awal' => '-',
                    'bk_foto_akhir' => '-',
                    'bk_nama_penggunan' => $request->tiket_pelanggan,
                    'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                    'bk_admin_input' => $admin_closed,
                    'bk_penerima' => 'Teknisi',
                    'bk_status' => 1,
                    'bk_keterangan' => $db->barang_ket,
                    'bk_harga' => $db->barang_harga,
                ]);
            }
            foreach ($data_barang as $db) {
                Data_Barang::where('barang_id', $db->barang_id)->update([
                    'barang_nama_pengguna' => $request->tiket_pelanggan,
                    'barang_digunakan' => $db->barang_digunakan + $request->tiket_total_kabel,
                    'barang_status' => '1',
                ]);
            }
        }
        Data_Tiket::where('tiket_id', $id)->update($tiket);

        $notifikasi = array(
            'pesan' => 'Tiket berhasil di update',
            'alert' => 'success',
        );
        return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
    }
}
