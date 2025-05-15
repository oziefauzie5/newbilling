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
        // $data['no_tiket'] = (new GlobalController)->nomor_tiket();
        $data['data_site'] = Data_Site::where('site_status','Enable')->get();
        $data['input_data'] = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')->where('reg_progres','=',5)->get();
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
        $tiket_id = (new GlobalController)->nomor_tiket();
        $tiket['tiket_id'] = $tiket_id;
        $tiket['tiket_pembuat'] = $pembuat;
        $tiket['tiket_kode'] = 'T-' . $tiket_id;
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


        $pesan_pelanggan['layanan'] = 'NOC';
        $pesan_pelanggan['ket'] = 'tiket';
        $pesan_pelanggan['pesan_id_site'] = $request->tiket_site;
        $pesan_pelanggan['target'] = $data['data_pelanggan']->input_hp;
        $pesan_pelanggan['status'] = $status_pesan;
        $pesan_pelanggan['nama'] = $data['data_pelanggan']->input_nama;
        $pesan_pelanggan['pesan'] = '               -- TIKET '.strtoupper($request->tiket_jenis).' --

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
            'target' =>  env('GROUP_TEKNISI'),
            'status' =>  $status_pesan,
            'nama' =>  'GROUP TEKNISI',
            'pesan' => '               -- TIKET '.strtoupper($request->tiket_jenis).' --

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

Antrian tiket = ' . $count . '
'
        ]);


        Data_Tiket::create($tiket);

        if ($request->tiket_jenis == 'Reaktivasi') {
            Registrasi::where('reg_idpel', $request->tiket_idpel)->update([
                'reg_progres' => 2,
                'reg_tgl_pasang'=>'',
                'reg_tgl_tagih'=>'',
                'reg_tgl_jatuh_tempo'=>'',
                'reg_out_odp'=>'',
                'reg_in_ont'=>'',
                'reg_los_opm'=>'',
                'reg_onuid'=>'',
                'reg_teknisi_team'=>'',
                'reg_odp'=>'',
            ]);
            $notifikasi = [
                'pesan' => 'Berhasil Membuat Tiket Deaktivasi',
                'alert' => 'success',
            ];
            return redirect()->route('admin.psb.index')->with($notifikasi);
        } else {
            $notifikasi = [
                'pesan' => 'Berhasil Membuat Tiket',
                'alert' => 'success',
            ];
            return redirect()->route('admin.tiket.data_tiket')->with($notifikasi);
        }
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
        $user = (new globalController)->user_admin();
        $data['user_nama'] = $user['user_nama'];
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
        // $no_tiket = (new GlobalController)->nomor_tiket();
        $datetime = date('Y-m-d h:m:s', strtotime(carbon::now()));

        // $admin_closed = Auth::user()->id;
        if($request->tiket_nama == 'Instalasi PSB'){
                $teknisi_id = '';
                $teknisi_nama = '';
                 $tiket['tiket_status'] = 'Aktivasi';
                
            } elseif($request->tiket_nama == 'Reaktivasi layanan'){
                 $teknisi_id = '';
                $teknisi_nama = '';
                 $tiket['tiket_status'] = 'Aktivasi';
                //  $reg['reg_progres'] = 2;

            } else {
                $explode = explode('|', $request->tiket_teknisi1);
                $teknisi_id = $explode[0];
                $teknisi_nama = $explode[1];
                $tiket['tiket_teknisi1'] = $teknisi_id;
                $tiket['tiket_teknisi2'] = $request->tiket_teknisi2;
                 $photo = $request->file('tiket_foto');
                $filename = $photo->getClientOriginalName();
                $path = 'laporan-kerja/' . $filename;
                Storage::disk('public')->put($path, file_get_contents($photo));
                $tiket['tiket_foto'] = $filename;
                $tiket['tiket_status'] = $request->tiket_status;

        }
       

        $tiket['tiket_jenis'] = $request->tiket_jenis;
        $tiket['tiket_pending'] = $request->tiket_pending;
        

        if ($request->tiket_status == 'Closed') {
            
            $tiket['tiket_waktu_selesai'] = $datetime;
            $tiket['tiket_kendala'] = $request->tiket_kendala;
            $tiket['tiket_tindakan'] = $request->tiket_tindakan;
            $tiket['tiket_waktu_mulai'] = $datetime;
            $barang['tiket_total_kabel'] = $request->tiket_total_kabel;


            $reg['reg_pop'] = $request->tiket_pop;
            $reg['reg_olt'] = $request->tiket_olt;
            $reg['reg_odc'] = $request->tiket_odc;
            $reg['reg_odp'] = $request->tiket_odp;
            // if ($request->tiket_jenis == 'Reaktivasi') {
            //     $reg['reg_progres'] = 2;
            // } else{
            //     $tiket['tiket_teknisi1'] = $teknisi_id;
            //     $tiket['tiket_teknisi2'] = $request->tiket_teknisi2;
            // }

       
            if ($request->kate_tindakan == 'Ganti ONT') {
                Data_BarangKeluar::whereIn('bk_id_barang', $request->kode_barang_ont)->delete();
                Data_Barang::whereIn('barang_id', $request->kode_barang_ont)->update([
                    'barang_digunakan' => 0,
                    'barang_dicek' => 1,
                    'barang_ket' => 'Ganti Perangkat',
                ]);
            } elseif ($request->kate_tindakan == 'Ganti Adaptor') {
                Data_BarangKeluar::whereIn('bk_id_barang', $request->kode_barang_adp)->delete();
                Data_Barang::whereIn('barang_id', $request->kode_barang_adp)->update([
                    'barang_digunakan' => 0,
                    'barang_hilang' => 1,
                ]);
            }

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
            $pesan_closed['target'] = env('GROUP_TEKNISI');
            $pesan_closed['nama'] = 'Group Teknisi';
            $pesan_closed['pesan'] = '               -- CLOSED TIKET --
            
Problem : ' . $request->tiket_kendala . '
Action : ' . $request->tiket_tindakan . '

Finish Time: ' . date('d-M-y h:m') . '
Technician : ' . $teknisi_nama . ' & ' . $request->tiket_teknisi2 . '

' . $request->tiket_menunggu . '';

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

    public function tiket_cek_ont(Request $request, $id)
    {
        // 
        $cek_mac = Data_Barang::where('barang_mac', $request->mac)->where('barang_kategori', 'ONT')->first();
        if ($cek_mac) {
            $data_barang_keluar = Data_BarangKeluar::where('bk_id_barang', $cek_mac->barang_id)->first();
            if ($data_barang_keluar) {
                if ($data_barang_keluar->bk_idpel == $id) {
                    $data['barang_id_ont'] = $data_barang_keluar->bk_id_barang;
                    $data['barang_sn'] = $cek_mac->barang_sn;
                    $data['barang_id'] = $cek_mac->barang_id;
                    return response()->json($data);
                } else {
                    return response()->json('0');
                }
            } else {
                return response()->json('1');
            }
        } else {
            return response()->json('2');
        }
    }
    public function tiket_cek_adp($id)
    {
        // $cek_mac = Data_Barang::where('barang_mac',$request->mac)->where('barang_kategori','ONT')->first();
        // if($cek_mac){
        $data_barang_keluar = Data_BarangKeluar::Join('data__barangs', 'data__barangs.barang_id', '=', 'data__barang_keluars.bk_id_barang')
            ->where('bk_idpel', $id)->where('bk_kategori', 'ONT')->first();
        // return response()->json($data_barang_keluar);
        if ($data_barang_keluar) {
            $dbk_adp = Data_BarangKeluar::where('bk_idpel', $id)->where('bk_kategori', 'ADAPTOR')->first();
            $data['barang_id_adp'] = $dbk_adp->bk_id_barang;
            $data['barang_id_ont'] = $data_barang_keluar->barang_id;
            $data['barang_sn'] = $data_barang_keluar->barang_sn;
            $data['barang_mac'] = $data_barang_keluar->barang_mac;
            return response()->json($data);
        } else {
            return response()->json('1');
        }
        // } else {
        //     return response()->json('2');
        // }
    }
}
