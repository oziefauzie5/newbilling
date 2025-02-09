<?php

namespace App\Http\Controllers\Tiket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Aplikasi\Data_Site;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\Pesan\Pesan;
use App\Models\Applikasi\SettingAplikasi;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
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
use Telegram\Bot\Api;

class TiketController extends Controller
{
    public function dashboard_tiket(Request $request)
    {
        $date = date('Y-m-d', strtotime(carbon::now()));
        $query_tiket = Data_Tiket::where('tiket_status', 'NEW')->where('tiket_type', 'General');
        $data['count_tiket_general'] = $query_tiket->count();
        $data['count_tiket_general_hari_ini'] = $query_tiket->whereDate('created_at', $date)->count();

        $query_tiket_project = Data_Tiket::where('tiket_status', 'NEW')->where('tiket_type', 'Project');
        $data['count_tiket_project'] = $query_tiket_project->count();
        $data['count_tiket_project_hari_ini'] = $query_tiket_project->whereDate('created_at', $date)->count();

        $query_tiket_closed = Data_Tiket::where('tiket_status', 'Closed')->where('tiket_type', 'General');
        $data['count_tiket_closed'] = $query_tiket_closed->count();
        $data['count_tiket_closed_hari_ini'] = $query_tiket_closed->whereDate('created_at', $date)->count();
        $query_tiket_pending = Data_Tiket::where('tiket_status', 'Closed')->where('tiket_type', 'General');
        $data['count_tiket_pending'] = $query_tiket_pending->count();
        $data['count_tiket_pending_hari_ini'] = $query_tiket_pending->whereDate('created_at', $date)->count();

        return view('tiket/dashboard_tiket', $data);
    }
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
    public function data_tiket_project(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Data_Tiket::join('users', 'users.id', '=', 'data__tikets.tiket_pembuat')
            ->select('users.id', 'users.name', 'data__tikets.*', 'data__tikets.created_at as tanggal')
            ->orderBy('tanggal', 'DESC', 'data__tikets.tiket_status', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('tiket_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('tiket_status', 'like', '%' . $data['q'] . '%');
                $query->orWhere('data__tikets.created_at', 'like', '%' . $data['q'] . '%');
            });
        $data['tiket'] = $query->paginate(10);
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->get();

        return view('tiket/data_tiket_project', $data);
    }
    public function buat_tiket(Request $request)
    {
        $data['no_tiket'] = (new GlobalController)->nomor_tiket();
        $data['data_site'] = Data_Site::all();
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

        $site = Auth::user()->site;
        $pembuat = Auth::user()->id;
        $tiket['tiket_id'] = $request->tiket_id;
        $tiket['tiket_pembuat'] = $pembuat;
        $tiket['tiket_kode'] = 'T-' . $request->tiket_id;
        $tiket['tiket_idpel'] = $request->tiket_idpel;
        $tiket['tiket_jenis'] = $request->tiket_jenis;
        $tiket['tiket_type'] = 'General';
        $tiket['tiket_site'] = $request->tiket_site;
        $tiket['tiket_nama'] = $request->tiket_nama;
        $tiket['tiket_jadwal_kunjungan'] = date('Y-m-d', strtotime($request->tiket_waktu_kunjungans));
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




        // foreach ($users_teknisi as $t) {

        //             Pesan::create([
        //                 'layanan' =>  'NOC',
        //                 'pesan_id_site' =>  $request->tiket_site,
        //                 'ket' =>  'tiket',
        //                 'target' =>  $t->hp,
        //                 'status' =>  $status_pesan,
        //                 'nama' =>  $t->nama_teknisi,
        //                 'pesan' => '               -- TIKET GANGGUAN --

        // Hallo Broo ' . $t->nama_teknisi . '
        // Ada tiket masuk ke sistem nih! 😊

        // No. Tiket : *' . $tiket['tiket_kode'] . '*
        // Topik : ' . $request->tiket_nama . '
        // Keterangan : *' . $request->tiket_keterangan . '*
        // Tgl Kunjungan : *' . $request->tiket_waktu_kunjungan . '*

        // No. Layanan : ' . $data['data_pelanggan']->reg_nolayanan . '
        // Pelanggan : ' . $request->tiket_pelanggan . '
        // Alamat : ' . $data['data_pelanggan']->input_alamat_pasang . '
        // Maps : https://www.google.com/maps/place/' . $maps . '
        // Whatsapp : 0' . $data['data_pelanggan']->input_hp . '
        // Tanggal tiket : ' . $tanggal . '

        // Semangat Broooo... Sisa tiket ' . $count . '
        // '
        //             ]);
        // }

        $pesan_pelanggan['layanan'] = 'NOC';
        $pesan_pelanggan['ket'] = 'tiket';
        $pesan_pelanggan['pesan_id_site'] = $request->tiket_site;
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
            'layanan' =>  'NOC',
            'pesan_id_site' =>  $request->tiket_site,
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


        //         $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        //         $chatId = '-4626560578';
        //         $message = '               -- TIKET GANGGUAN --

        // Hallo Broo.....
        // Ada tiket masuk ke sistem nih! 😊

        // No. Tiket : *' . $tiket['tiket_kode'] . '*
        // Topik : ' . $request->tiket_nama . '
        // Keterangan : *' . $request->tiket_keterangan . '*
        // Tgl Kunjungan : *' . $request->tiket_waktu_kunjungan . '*

        // No. Layanan : ' . $data['data_pelanggan']->reg_nolayanan . '
        // Pelanggan : ' . $request->tiket_pelanggan . '
        // Alamat : ' . $data['data_pelanggan']->input_alamat_pasang . '

        // Maps : https://www.google.com/maps/place/' . $maps . '

        // Whatsapp : 0' . $data['data_pelanggan']->input_hp . '

        // Tanggal tiket : ' . $tanggal . '

        // Semangat Broooo... Sisa tiket = ' . $count . '
        // ';

        //         $reponse = $telegram->sendMessage([
        //             'chat_id' => $chatId,
        //             'text' => $message,
        //         ]);




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

        $data['data_user'] = User::all();
        $data['teknisi'] = (new GlobalController)->getTeknisi();
        $data['user_admin'] = (new GlobalController)->user_admin();
        $data['tiket'] = Data_Tiket::join('registrasis', 'registrasis.reg_idpel', '=', 'data__tikets.tiket_idpel')
            ->join('input_data', 'input_data.id', '=', 'data__tikets.tiket_idpel')
            ->join('data__sites', 'data__sites.site_id', '=', 'data__tikets.tiket_site')
            ->select('data__sites.site_id', 'data__sites.site_nama', 'data__tikets.*', 'input_data.*', 'registrasis.*', 'data__tikets.created_at as tgl_buat')
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
    public function details_tiket_project($id)
    {
        $data['teknisi'] = (new GlobalController)->getTeknisi();
        $data['tiket'] = Data_Tiket::query()
            ->join('data__barang_keluars', 'data__barang_keluars.bk_id_tiket', '=', 'data__tikets.tiket_id')
            ->select('data__tikets.*', 'data__barang_keluars.*', 'data__tikets.created_at as tgl_buat')
            ->where('tiket_id', $id)
            ->first();
        $query = Data_Tiket::query()
            ->select('data__tikets.*', 'data__tikets.created_at as tgl_buat')
            ->where('tiket_status', 'NEW')
            ->where('tiket_type', 'Project')
            ->where('tiket_id', '!=', $id);
        $data['tiket_menunggu'] = $query->get();
        $data['tiket_count'] = $query->count();
        return view('tiket/details_tiket_project', $data);
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
            $tiket['tiket_waktu_mulai'] = $datetime;
            $tiket['tiket_waktu_selesai'] = $datetime;

            $reg['reg_pop'] = $request->tiket_pop;
            $reg['reg_olt'] = $request->tiket_olt;
            $reg['reg_odc'] = $request->tiket_odc;
            $reg['reg_odp'] = $request->tiket_odp;
            if ($request->tiket_jenis == 'Reaktivasi') {
                $reg['reg_progres'] = 2;
            }

            $barang['tiket_total_kabel'] = $request->tiket_total_kabel;

            $photo = $request->file('tiket_foto');
            $filename = $photo->getClientOriginalName();
            $path = 'laporan-tiket/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($photo));
            $tiket['tiket_foto'] = $filename;


            $status = (new GlobalController)->whatsapp_status();
            if ($status->wa_status == 'Enable') {
                $status_pesan = '0';
            } else {
                $status_pesan = '10';
            }
            $pesan_closed['layanan'] = 'NOC';
            $pesan_closed['ket'] = 'tiket';
            $pesan_closed['pesan_id_site'] = $request->tiket_site;
            $pesan_closed['status'] = $status_pesan;
            $pesan_closed['target'] = '120363028776966861@g.us';
            $pesan_closed['nama'] = 'Group Teknisi';
            $pesan_closed['pesan'] = '               -- CLOSED TIKET --
Kendala : ' . $request->tiket_kendala . '
Tindakan : ' . $request->tiket_tindakan . '

Waktu selesai: ' . date('d-M-y h:m') . '
Dikerjakan Oleh : ' . $teknisi_nama . ' & ' . $request->tiket_teknisi2 . '

' . $request->tiket_menunggu . '';




            //             $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            //             $chatId = '-4626560578';
            //             $message = '               -- CLOSED TIKET --
            // Kendala : ' . $request->tiket_kendala . '
            // Tindakan : ' . $request->tiket_tindakan . '

            // Waktu selesai: ' . date('d-M-y h:m') . '
            // Dikerjakan Oleh : ' . $teknisi_nama . ' & ' . $request->tiket_teknisi2 . '

            // ' . $request->tiket_menunggu . '';

            //             $reponse = $telegram->sendMessage([
            //                 'chat_id' => $chatId,
            //                 'text' => $message,
            //             ]);



            Registrasi::where('reg_nolayanan', $request->tiket_nolayanan)->update($reg);
            // dd($reg);
            Data_Tiket::where('tiket_id', $id)->update($tiket);
            Pesan::create($pesan_closed);



            $notifikasi = array(
                'pesan' => 'Tiket berhasil di update',
                'alert' => 'success',
            );
            return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
        }
    }
}
