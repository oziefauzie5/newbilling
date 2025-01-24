<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Pesan\Pesan;
use App\Models\Applikasi\SettingAplikasi;
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
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;

class TiketController extends Controller
{
    public function data_tiket(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Data_Tiket::join('users', 'users.id', '=', 'data__tikets.tiket_pembuat')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->select('users.id', 'users.name', 'registrasis.*', 'input_data.*', 'data__tikets.*', 'data__tikets.created_at as tanggal')
            ->orderBy('tanggal', 'DESC', 'data__tikets.tiket_status', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('tiket_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('input_data.input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('registrasis.reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('tiket_status', 'like', '%' . $data['q'] . '%');
                $query->orWhere('data__tikets.created_at', 'like', '%' . $data['q'] . '%');
            });
        $data['tiket'] = $query->paginate(10);
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();

        return view('tiket/data_tiket', $data);
    }
    public function buat_tiket(Request $request)
    {
        $data['no_tiket'] = (new GlobalController)->nomor_tiket();
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();
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
        $count = Data_Tiket::where('tiket_status', 'NEW')->count();
        $status = (new GlobalController)->whatsapp_status();
        if ($status->wa_status == 'Enable') {
            $status_pesan = '0';
        } else {
            $status_pesan = '10';
        }




        foreach ($users_teknisi as $t) {

            Pesan::create([
                'ket' =>  'tiket',
                'target' =>  $t->hp,
                'status' =>  $status_pesan,
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

Semangat Broooo... Sisa tiket ' . $count . '
'
            ]);
        }

        $pesan_pelanggan['ket'] = 'tiket';
        $pesan_pelanggan['target'] = $data['data_pelanggan']->input_hp;
        $pesan_pelanggan['status'] = $status_pesan;
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

        Pesan::create($pesan_pelanggan);


        Pesan::create([
            'ket' =>  'tiket',
            'target' =>  '120363028776966861@g.us',
            'status' =>  $status_pesan,
            'nama' =>  'GROUP TEKNISI',
            'pesan' => '               -- TIKET GANGGUAN --

Hallo Broo.....
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

Semangat Broooo... Sisa tiket = ' . $count . '
'
        ]);


        Data_Tiket::create($tiket);
        $notifikasi = [
            'pesan' => 'Berhasil Membuat Tiket',
            'alert' => 'success',
        ];
        return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
    }


    public function export_tiket(Request $request)
    {
        $data['profile_perusahaan'] = SettingAplikasi::first();
        $data['nama_admin'] = Auth::user()->name;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        // dd($request->start_date);
        $data['tiket'] = Data_Tiket::select('users.id as id_pembuat', 'users.name', 'registrasis.*', 'input_data.*', 'data__tikets.*', 'data__tikets.created_at as tanggal')
            // ->join('users', 'users.id', '=', 'data__tikets.tiket_pembuat')
            ->join('users', 'users.id', '=', 'data__tikets.tiket_teknisi1')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->orderBy('tanggal', 'DESC', 'data__tikets.tiket_status', 'DESC')
            ->whereDate('data__tikets.created_at', '>=', date('Y-m-d', strtotime($data['start_date'])))
            ->whereDate('data__tikets.created_at', '<=', date('Y-m-d', strtotime($data['end_date'])))
            ->get();
        $pdf = App::make('dompdf.wrapper');
        $html = view('tiket/print_tiket', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Laporan Tiket ' . date('d-m-Y', strtotime($data['start_date'])) . ' - ' . date('d-m-Y', strtotime($data['end_date'])) . '.pdf');
        return view('tiket/print_tiket', $data);
    }
    public function details_tiket($id)
    {


        $data['teknisi'] = (new GlobalController)->getTeknisi();
        $data['tiket'] = Data_Tiket::join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->select('data__tikets.*', 'input_data.*', 'registrasis.*', 'data__tikets.created_at as tgl_buat')
            ->where('tiket_id', $id)
            ->first();
        $query = Data_Tiket::join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->select('data__tikets.*', 'input_data.*', 'data__tikets.created_at as tgl_buat')
            ->where('tiket_status', 'NEW')
            ->where('tiket_id', '!=', $id);
        $data['tiket_menunggu'] = $query->get();
        $data['tiket_count'] = $query->count();
        return view('tiket/details_tiket', $data);
    }
    public function details_tiket_closed($id)
    {
        $data['teknisi'] = (new GlobalController)->getTeknisi();
        $data['tiket'] = Data_Tiket::join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->join('users', 'users.id', '=', 'data__tikets.tiket_teknisi1')
            ->select('data__tikets.*', 'input_data.*', 'data__tikets.created_at as tgl_buat', 'users.id', 'users.name')
            ->where('tiket_id', $id)
            ->first();
        return view('tiket/details_tiket_closed', $data);
    }

    public function tiket_update(Request $request, $id)
    {

        // dd($id);
        // dd($request->tiket_barang_id);
        $no_sk = (new GlobalController)->no_surat_keterang();
        $no_tiket = (new GlobalController)->nomor_tiket();
        $datetime = date('Y-m-d h:m:s', strtotime(carbon::now()));

        $admin_closed = Auth::user()->id;

        $explode = explode('|', $request->tiket_teknisi1);
        $teknisi_id = $explode[0];
        $teknisi_nama = $explode[1];

        $tiket['tiket_jenis'] = $request->tiket_jenis;
        $tiket['tiket_status'] = $request->tiket_status;
        $tiket['tiket_pending'] = $request->tiket_pending;
        $tiket['tiket_teknisi1'] = $teknisi_id;
        $tiket['tiket_teknisi2'] = $request->tiket_teknisi2;

        if ($request->tiket_status == 'Closed') {
            $tiket['tiket_waktu_selesai'] = $datetime;
            $tiket['tiket_kendala'] = $request->tiket_kendala;
            $tiket['tiket_tindakan'] = $request->tiket_tindakan;
            $tiket['tiket_waktu_penanganan'] = $datetime;
            $tiket['tiket_waktu_selesai'] = $datetime;

            $barang['tiket_total_kabel'] = $request->tiket_total_kabel;

            $photo = $request->file('tiket_foto');
            $filename = $photo->getClientOriginalName();
            $path = 'laporan-tiket/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $tiket['tiket_foto'] = $filename;

            if ($request->ganti_barang == 1) {
                $data_barang = Data_Barang::whereIn('barang_id', [$request->tiket_barang1, $request->tiket_barang2, $request->tiket_barang3, $request->tiket_barang4])->get();


                foreach ($data_barang as $db) {
                    Data_BarangKeluar::create([
                        'bk_id' => $no_sk,
                        'bk_jenis_laporan' => $request->tiket_jenis,
                        'bk_id_barang' => $db->barang_id,
                        'bk_id_tiket' => $no_tiket,
                        'bk_kategori' => $db->barang_kategori,
                        'bk_satuan' => $db->barang_satuan,
                        'bk_nama_barang' => $db->barang_nama,
                        'bk_model' => $db->barang_merek,
                        'bk_mac' => $db->barang_mac,
                        'bk_sn' => $db->barang_sn,
                        'bk_jumlah' => $request->barang_jumlah,
                        'bk_keperluan' => $request->tiket_jenis,
                        'bk_foto_awal' => '-',
                        'bk_foto_akhir' => '-',
                        'bk_nama_penggunan' => '',
                        'bk_waktu_keluar' => date('Y-m-d H:m:s', strtotime(Carbon::now())),
                        'bk_admin_input' => $admin_closed,
                        'bk_penerima' => $teknisi_nama,
                        'bk_status' => 1,
                        'bk_keterangan' => '',
                        'bk_harga' => $request->barang_jumlah,
                    ]);
                    Data_Barang::whereIn('barang_id', [$request->barang_id])->update(
                        [
                            'barang_nama_pengguna' => $db->barang_id,
                            'barang_digunakan' => '1',
                            'barang_status' => '1',
                        ]
                    );
                }
            }



            $status = (new GlobalController)->whatsapp_status();
            if ($status->wa_status == 'Enable') {
                $status_pesan = '0';
            } else {
                $status_pesan = '10';
            }
            $pesan_closed['ket'] = 'tiket';
            $pesan_closed['status'] = $status_pesan;
            $pesan_closed['target'] = '120363028776966861@g.us';
            $pesan_closed['nama'] = 'Group Teknisi';
            $pesan_closed['pesan'] = '               -- CLOSED TIKET --
Kendala : ' . $request->tiket_kendala . '
Tindakan : ' . $request->tiket_tindakan . '

Waktu selesai: ' . date('d-M-y h:m') . '
Dikerjakan Oleh : ' . $teknisi_nama . ' & ' . $request->tiket_teknisi2 . '

' . $request->tiket_menunggu . '';


            Pesan::create($pesan_closed);



            Data_Tiket::where('tiket_id', $id)->update($tiket);



            $notifikasi = array(
                'pesan' => 'Tiket berhasil di update',
                'alert' => 'success',
            );
            return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
        }
    }
}
