<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Barang\SubBarang;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\RouterosAPI;
use App\Models\Teknisi\Teknisi;
use App\Models\Tiket\SubTiket;
use App\Models\Tiket\Tiket;
use App\Models\Transaksi\Addons;
use App\Models\Pesan\Pesan;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $teknisi_id = Auth::user()->id;

        $data['q'] = $request->query('q');
        $query = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_progres', '=', '0')
            ->orderBy('input_data.input_tgl', 'DESC');

        $data['data_pelanggan'] = $query->get();

        $data['data_tiket'] = Tiket::select('registrasis.*', 'input_data.*', 'tikets.*', 'tikets.created_at as tgl_tiket')
            ->join('registrasis', 'registrasis.reg_nolayanan', '=', 'tikets.tiket_nolayanan')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('tiket_status', '!=', 'DONE')->get();

        $data['data_user'] = DB::table('roles')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'users.id', '=', 'model_has_roles.model_id')
            ->where('roles.name', '=', 'teknisi')
            ->where('users.id', '=', $teknisi_id)
            ->get();

        return view('Teknisi/index', $data);
    }

    public function job(Request $request)
    {
        $request->validate([
            'teknisi_id' => 'unique:teknisis',
        ], [
            'teknisi_id.unique' => 'Ulangi kembali',
        ]);

        $explode1 = explode("|", $request->teknisi);
        $team = $explode1[1] . ' & ' . ucwords($request->sub_teknisi);
        $id = strtotime(Carbon::now());
        $data['teknisi_id'] = $id;
        $data['teknisi_userid'] = $explode1[0];
        $data['teknisi_team'] = $team;
        $data['teknisi_job'] = $request->job;
        $data['teknisi_idpel'] = $request->idpel;
        $data['teknisi_status'] = '1';
        $data['teknisi_psb'] = '0';
        Teknisi::create($data);
        $update['reg_progres'] = '1';
        $update['reg_teknisi_team'] = $team;
        Registrasi::where('reg_idpel', $request->idpel)->update($update);
        $notifikasi = [
            'pesan' => 'Berhasil mengambil job',
            'alert' => 'success',
        ];
        return redirect()->route('admin.teknisi.list_aktivasi')->with($notifikasi);
    }
    public function job_tiket(Request $request)
    {
        $request->validate([
            'teknisi_id' => 'unique:teknisis',
        ], [
            'teknisi_id.unique' => 'Ulangi kembali',
        ]);

        $explode1 = explode("|", $request->teknisi);
        $team = $explode1[1] . ' & ' . ucwords($request->sub_teknisi);
        $id = strtotime(Carbon::now());
        $data['teknisi_id'] = $id;
        $data['teknisi_userid'] = $explode1[0];
        $data['teknisi_team'] = $team;
        $data['teknisi_job'] = $request->job;
        $data['teknisi_idpel'] = $request->idpel;
        $data['teknisi_status'] = '1';
        $data['teknisi_psb'] = '0';
        Teknisi::create($data);
        $update['tiket_status'] = 'PROGRES';
        Tiket::where('tiket_id', $request->tiket_id)->update($update);
        $updates['subtiket_id'] = $request->tiket_id;
        $updates['subtiket_status'] = 'PROGRES';
        $updates['subtiket_admin'] = $explode1[0];
        $updates['subtiket_teknisi_team'] = $team;
        $updates['subtiket_deskripsi'] = 'Melakukan Pengecekan';
        SubTiket::create($updates);
        $notifikasi = [
            'pesan' => 'Berhasil mengambil job',
            'alert' => 'success',
        ];
        return redirect()->route('admin.teknisi.list_tiket')->with($notifikasi);
    }
    public function update_tiket($tiket_id)
    {
        $teknisi_id = Auth::user()->id;
        $cek = SubTiket::where('subtiket_admin', $teknisi_id)->where('subtiket_id', $tiket_id)->where('subtiket_status', 'OPEN')->first();
        // return response()->json($cek);
        if (!$cek) {
            $teknisi_name = Auth::user()->name;
            $update['tiket_status'] = 'OPEN';
            Tiket::where('tiket_id', $tiket_id)->update($update);
            $updates['subtiket_id'] = $tiket_id;
            $updates['subtiket_status'] = 'OPEN';
            $updates['subtiket_admin'] = $teknisi_id;
            $updates['subtiket_teknisi_team'] = $teknisi_name;
            $updates['subtiket_deskripsi'] = 'Membuka Tiket';
            SubTiket::create($updates);
        }
    }

    public function list_aktivasi()
    {
        $teknisi_id = Auth::user()->id;
        $query = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'input_data.id')
            ->where('registrasis.reg_progres', '=', '1')
            ->where('teknisis.teknisi_userid', '=', $teknisi_id);
        $data['data_pelanggan'] = $query->get();
        return view('Teknisi/list_aktivasi', $data);
    }
    public function list_tiket()
    {
        $teknisi_id = Auth::user()->id;
        $query = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'input_data.id')
            ->join('tikets', 'tikets.tiket_idpel', '=', 'input_data.id')
            ->where('tikets.tiket_status', '=', 'PROGRES')
            ->where('teknisis.teknisi_JOB', '=', 'TIKET')
            ->where('teknisis.teknisi_userid', '=', $teknisi_id);
        $data['data_pelanggan'] = $query->get();
        return view('Teknisi/list_tiket', $data);
    }
    public function details($id)
    {
        $teknisi_id = Auth::user()->id;
        $query = InputData::select('registrasis.*', 'teknisis.*', 'input_data.*', 'tikets.*', 'tikets.created_at as tgl_dibuat')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'input_data.id')
            ->join('tikets', 'tikets.tiket_idpel', '=', 'input_data.id')
            ->where('tikets.tiket_status', '=', 'PROGRES')
            ->where('teknisis.teknisi_JOB', '=', 'TIKET')
            ->where('teknisis.teknisi_userid', '=', $teknisi_id);
        $data['tiket'] = $query->first();
        $data['subtiket'] = SubTiket::join('users', 'users.id', '=', 'subtiket_admin')
            ->select('users.*', 'sub_tikets.*', 'sub_tikets.created_at as tgl_progres')
            ->where('subtiket_id', $id)->get();
        return view('Teknisi/details_tiket', $data);
    }
    public function aktivasi($id)
    {
        $query = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'input_data.id')
            ->where('registrasis.reg_progres', '=', '1')
            ->where('teknisis.teknisi_idpel', '=', $id);
        $data['data_aktivasi'] = $query->first();

        return view('Teknisi/aktivasi', $data);
    }

    public function proses_aktivasi(Request $request, $id)
    {
        Session::flash('before', $request->before);
        Session::flash('after', $request->after);
        Session::flash('total', $request->total);
        Session::flash('fat', $request->fat);
        Session::flash('fat_opm', $request->fat_opm);
        Session::flash('home_opm', $request->home_opm);
        Session::flash('los_opm', $request->los_opm);
        Session::flash('kode', $request->kode);
        Session::flash('id', $request->id);



        $id_teknisi = Auth::user()->id;
        $teknisi_nama = Auth::user()->name;
        $swaktu = SettingWaktuTagihan::first();
        $sbiaya = SettingBiaya::first();
        $barang = SubBarang::where('id_subbarang', $request->kode)->first();
        $countReg = Registrasi::count();
        $frefixid = Session::get('app_clientid');

        if ($countReg = 0) {
            $idclient = $frefixid . '1';
        } else {
            $idclient = $frefixid . $countReg + 1;
        }
        if ($barang->subbarang_stok > $request->after) {


            $query = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
                ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
                ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                ->where('registrasis.reg_idpel', $id)
                ->first();
            $tagihan_tanpa_ppn = $query->reg_harga + $query->reg_dana_kas + $query->reg_dana_kerjasama + $query->reg_kode_unik;

            #FORMAT TANGGAL
            $tanggal = Carbon::now()->toDateString();
            $tag_pascabayar = Carbon::create($tanggal)->addMonth(1)->toDateString();
            $tag_free3bln = Carbon::create($tanggal)->addMonth(3)->toDateString();
            $tag_free1th = Carbon::create($tanggal)->addMonth(12)->toDateString();
            $inv_tgl_tagih_pascabayar = Carbon::create($tag_pascabayar)->addDay(-$swaktu->wt_jeda_tagihan_pertama)->toDateString();
            $inv_tgl_isolir_pascabayar = Carbon::create($tag_pascabayar)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

            $inv_tgl_isolir1blan = Carbon::create($tanggal)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir3blan = Carbon::create($tag_free3bln)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
            $inv_tgl_isolir12blan = Carbon::create($tag_free1th)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();

            $periode3blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(3)->toDateString();
            $periode12blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(12)->toDateString();
            $periode1blan = Carbon::create($tanggal)->toDateString() . ' - ' . Carbon::create($tanggal)->addMonth(1)->toDateString();

            #API MIKROTIK
            $ip =   $query->router_ip . ':' . $query->router_port_api;
            $user = $query->router_username;
            $pass = $query->router_password;
            $API = new RouterosAPI();
            $API->debug = false;

            if ($query->reg_layanan == 'PPP') {

                if ($API->connect($ip, $user, $pass)) {
                    $secret = $API->comm('/ppp/secret/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($secret) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $secret[0]['.id'],
                            'disabled' => 'no',
                        ]);

                        if ($query->reg_jenis_tagihan == 'FREE') {
                            $teknisi['teknisi_psb'] = '0';
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir12blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn * 12 + ($query->reg_ppn * 12);
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_free1th;
                            $inv['inv_periode'] = $periode12blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '12';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'FREE 3 BULAN') {
                            $teknisi['teknisi_psb'] = '0';
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir3blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn * 3 + ($query->reg_ppn * 3);
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_free3bln;
                            $inv['inv_periode'] = $periode3blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '3';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'PRABAYAR') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'PASCABAYAR') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir_pascabayar;
                            $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                            $inv['inv_tgl_tagih'] = $inv_tgl_tagih_pascabayar;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_pascabayar;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'DEPOSIT') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                            $inv['inv_total'] = $query->reg_deposit;
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $inv['inv_total'];
                            $sub_inv['subinvoice_ppn'] = '0';
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                            $pelanggan['reg_deposit'] = $inv['inv_total'];
                        }

                        $update_barang['subbarang_keluar'] = $barang->subbarang_keluar + $request->total;
                        $update_barang['subbarang_stok'] = $barang->subbarang_stok - $request->total;

                        $pelanggan['reg_progres'] = '2';
                        $pelanggan['reg_fat'] = $request->fat;
                        $pelanggan['reg_fat_opm'] = $request->fat_opm;
                        $pelanggan['reg_home_opm'] = $request->home_opm;
                        $pelanggan['reg_los_opm'] = $request->los_opm;
                        $pelanggan['reg_kode_dropcore'] = $request->kode;
                        $pelanggan['reg_before'] = $request->before;
                        $pelanggan['reg_after'] = $request->after;
                        $pelanggan['reg_penggunaan_dropcore'] = $request->total;
                        $pelanggan['reg_tgl_pasang'] = $tanggal;
                        $pelanggan['reg_clientid'] = $idclient;

                        $teknisi['teknisi_ket'] = $query->input_nama;
                        $teknisi['teknisi_job_selesai'] = strtotime(Carbon::now());

                        $inv['inv_tgl_pasang'] = $tanggal;
                        $inv['inv_status'] = 'UNPAID';
                        $inv['inv_idpel'] = $query->reg_idpel;
                        $inv['inv_nolayanan'] = $query->reg_nolayanan;
                        $inv['inv_nama'] = $query->input_nama;
                        $inv['inv_jenis_tagihan'] = $query->reg_jenis_tagihan;
                        $inv['inv_profile'] = $query->paket_nama;
                        $inv['inv_mitra'] = 'SYSTEM';
                        $inv['inv_kategori'] = 'OTOMATIS';
                        $inv['inv_diskon'] = '0';
                        $inv['inv_note'] = $query->input_nama;



                        $sub_inv['subinvoice_deskripsi'] = $query->paket_nama . ' ( ' . $inv['inv_periode'] . ' )';
                        $sub_inv['subinvoice_status'] = '0';




                        #NILAI TEKNISI
                        $waktu_kerja = Teknisi::where('teknisi_idpel', $id)->where('teknisi_status', '1')->where('teknisi_userid', $id_teknisi)->first();
                        // dd($id_teknisi);

                        $awal  = $waktu_kerja->teknisi_id;
                        $akhir  = $teknisi['teknisi_job_selesai'];

                        $diff  = $akhir - $awal;
                        if ($diff > 10800) {
                            $nilai = '25';
                            $kata = '
"Manusia itu memiliki potensi dan kesempatan yang sama pula. Maka jangan menyerah untuk terus berusaha mendapatkan yang terbaik"';
                        } elseif ($diff > 7200 & $diff < 10800) {
                            $nilai = '50';
                            $kata = '
"Hanya karena belum ada yang berhasil melakukannya, bukan berarti kamu tidak mungkin mencapainya"';
                        } elseif ($diff > 3600 & $diff < 7200) {
                            $nilai = '75';
                            $kata = '
"Kami sangat berterima kasih atas dedikasi dan upaya Anda yang tiada henti untuk unggul dalam pekerjaan.  Kami harap Anda terus menginspirasi dan melambung lebih tinggi"';
                        } elseif ($diff < 3600) {
                            $nilai = '100';
                            $kata = '
"Kinerja luar biasa Anda di tempat kerja merupakan inspirasi bagi semua orang dan kami sangat terkesan dan bangga. Pertahankan kerja bagus Anda!"';
                        }

                        if ($request->los_opm > 3) {
                            $nilai2 = '25';
                        } elseif ($request->los_opm > 1 & $request->los_opm < 2) {
                            $nilai2 = '50';
                        } elseif ($request->los_opm < 1) {
                            $nilai2 = '100';
                        } else {
                            $nilai2 = '20';
                        }

                        $startTime = Carbon::parse($awal);
                        $endTime = Carbon::parse($akhir);
                        $duration = $endTime->diffInMinutes($startTime);
                        $totalminutes = $duration;


                        $teknisi['teknisi_waktu_kerja'] = date('H:i', mktime(0, $totalminutes));
                        $teknisi['teknisi_nilai'] = $nilai;
                        $teknisi['teknisi_nilai_instalasi'] = $nilai2;
                        $teknisi['teknisi_note'] = $kata;

                        $cek_inv = Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->first();
                        if ($cek_inv) {
                            $inv['inv_id'] = $cek_inv->inv_id;
                            $sub_inv['subinvoice_id'] = $inv['inv_id'];
                            Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->update($inv);
                            SubInvoice::where('subinvoice_id', $sub_inv['subinvoice_id'])->update($sub_inv);
                        } else {
                            $inv['inv_id'] = rand(10000, 19999);
                            $sub_inv['subinvoice_id'] = $inv['inv_id'];
                            Invoice::create($inv);
                            SubInvoice::create($sub_inv);
                        }
                        Registrasi::where('reg_idpel', $id)->update($pelanggan);
                        Teknisi::where('teknisi_idpel', $id)->where('teknisi_status', '1')->where('teknisi_userid', $id_teknisi)->update($teknisi);
                        SubBarang::where('id_subbarang', $request->kode)->update($update_barang);




                        $pesan_group['ket'] = 'aktivasi psb';
                        $pesan_group['status'] = '0';
                        $pesan_group['target'] = '120363028776966861@g.us';
                        $pesan_group['nama'] = 'GROUP TEKNISI OVALL';
                        $pesan_group['pesan'] = '               -- PSB SELESAI --

Pemasangan telah selesai dikerjakan  😊

Pelanggan : ' . $query->input_nama . '
Alamat : ' . $query->input_alamat_pasang . '


Teknisi Team : ' . $waktu_kerja->teknisi_team . '
FAT OPM : ' . $request->fat_opm . '
Home OPM : ' . $request->home_opm . '
Los OPM : ' . $request->los_opm . '

Kode Kabel : ' . $request->kode . '
Before Kabel : ' . $request->before . '
after Kabel : ' . $request->after . '
Panjang Kabel : ' . $request->total . '

Waktu Selesai : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
Waktu Pengerjaan : ' . date('H:i', mktime(0, $totalminutes)) . '

Diaktivasi Oleh : ' . $teknisi_nama . '
';


                        Pesan::create($pesan_group);

                        $notifikasi = array(
                            'pesan' => 'Aktivasi Berhasil ',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.teknisi.index')->with($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                            'alert' => 'error',
                        );
                        return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
                    }
                } else {
                    $notifikasi = array(
                        'pesan' => 'Router Discconect',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
                }
            } else {
                if ($API->connect($ip, $user, $pass)) {
                    $secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $query->reg_username,
                    ]);
                    if ($secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $secret[0]['.id'],
                            'disabled' => 'no',
                        ]);

                        if ($query->reg_jenis_tagihan == 'FREE') {
                            $teknisi['teknisi_psb'] = '0';
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir12blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn * 12 + ($query->reg_ppn * 12);
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_free1th;
                            $inv['inv_periode'] = $periode12blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '12';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'FREE 3 BULAN') {
                            $teknisi['teknisi_psb'] = '0';
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir3blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn * 3 + ($query->reg_ppn * 3);
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_free3bln;
                            $inv['inv_periode'] = $periode3blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '3';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'PRABAYAR') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                            $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'PASCABAYAR') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir_pascabayar;
                            $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
                            $inv['inv_tgl_tagih'] = $inv_tgl_tagih_pascabayar;
                            $inv['inv_tgl_jatuh_tempo'] = $tag_pascabayar;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $query->reg_harga;
                            $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                        } else if ($query->reg_jenis_tagihan == 'DEPOSIT') {
                            $teknisi['teknisi_psb'] = $sbiaya->biaya_psb;
                            $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
                            $inv['inv_total'] = $query->reg_deposit;
                            $inv['inv_tgl_tagih'] = $tanggal;
                            $inv['inv_tgl_jatuh_tempo'] = $tanggal;
                            $inv['inv_periode'] = $periode1blan;

                            $sub_inv['subinvoice_harga'] = $inv['inv_total'];
                            $sub_inv['subinvoice_ppn'] = '0';
                            $sub_inv['subinvoice_total'] = $inv['inv_total'];
                            $sub_inv['subinvoice_qty'] = '1';

                            $pelanggan['reg_tgl_jatuh_tempo'] = $inv['inv_tgl_jatuh_tempo'];
                            $pelanggan['reg_tgl_tagih'] = $inv['inv_tgl_tagih'];
                            $pelanggan['reg_deposit'] = $inv['inv_total'];
                        }

                        $update_barang['subbarang_keluar'] = $barang->subbarang_keluar + $request->total;
                        $update_barang['subbarang_stok'] = $barang->subbarang_stok - $request->total;

                        $pelanggan['reg_progres'] = '2';
                        $pelanggan['reg_fat'] = $request->fat;
                        $pelanggan['reg_fat_opm'] = $request->fat_opm;
                        $pelanggan['reg_home_opm'] = $request->home_opm;
                        $pelanggan['reg_los_opm'] = $request->los_opm;
                        $pelanggan['reg_kode_dropcore'] = $request->kode;
                        $pelanggan['reg_before'] = $request->before;
                        $pelanggan['reg_after'] = $request->after;
                        $pelanggan['reg_penggunaan_dropcore'] = $request->total;
                        $pelanggan['reg_tgl_pasang'] = $tanggal;
                        $pelanggan['reg_clientid'] = $idclient;

                        $teknisi['teknisi_ket'] = $query->input_nama;
                        $teknisi['teknisi_job_selesai'] = strtotime(Carbon::now());

                        $inv['inv_tgl_pasang'] = $tanggal;
                        $inv['inv_status'] = 'UNPAID';
                        $inv['inv_idpel'] = $query->reg_idpel;
                        $inv['inv_nolayanan'] = $query->reg_nolayanan;
                        $inv['inv_nama'] = $query->input_nama;
                        $inv['inv_jenis_tagihan'] = $query->reg_jenis_tagihan;
                        $inv['inv_profile'] = $query->paket_nama;
                        $inv['inv_mitra'] = 'SYSTEM';
                        $inv['inv_kategori'] = 'OTOMATIS';
                        $inv['inv_diskon'] = '0';
                        $inv['inv_note'] = $query->input_nama;



                        $sub_inv['subinvoice_deskripsi'] = $query->paket_nama . ' ( ' . $inv['inv_periode'] . ' )';
                        $sub_inv['subinvoice_status'] = '0';

                        #NILAI TEKNISI
                        $waktu_kerja = Teknisi::where('teknisi_idpel', $id)->where('teknisi_status', '1')->where('teknisi_userid', $id_teknisi)->first();
                        // dd($id_teknisi);

                        $awal  = $waktu_kerja->teknisi_id;
                        $akhir  = $teknisi['teknisi_job_selesai'];

                        $diff  = $akhir - $awal;
                        if ($diff > 10800) {
                            $nilai = '25';
                            $kata = '
"Manusia itu memiliki potensi dan kesempatan yang sama pula. Maka jangan menyerah untuk terus berusaha mendapatkan yang terbaik"';
                        } elseif ($diff > 7200 & $diff < 10800) {
                            $nilai = '50';
                            $kata = '
"Hanya karena belum ada yang berhasil melakukannya, bukan berarti kamu tidak mungkin mencapainya"';
                        } elseif ($diff > 3600 & $diff < 7200) {
                            $nilai = '75';
                            $kata = '
"Kami sangat berterima kasih atas dedikasi dan upaya Anda yang tiada henti untuk unggul dalam pekerjaan.  Kami harap Anda terus menginspirasi dan melambung lebih tinggi"';
                        } elseif ($diff < 3600) {
                            $nilai = '100';
                            $kata = '
"Kinerja luar biasa Anda di tempat kerja merupakan inspirasi bagi semua orang dan kami sangat terkesan dan bangga. Pertahankan kerja bagus Anda!"';
                        }

                        if ($request->los_opm > 3) {
                            $nilai2 = '25';
                        } elseif ($request->los_opm > 1 & $request->los_opm < 2) {
                            $nilai2 = '50';
                        } elseif ($request->los_opm < 1) {
                            $nilai2 = '100';
                        } else {
                            $nilai2 = '20';
                        }
                        $startTime = Carbon::parse($awal);
                        $endTime = Carbon::parse($akhir);
                        $duration = $endTime->diffInMinutes($startTime);
                        $totalminutes = $duration;

                        $teknisi['teknisi_waktu_kerja'] = date('H:i', mktime(0, $totalminutes));
                        $teknisi['teknisi_nilai'] = $nilai;
                        $teknisi['teknisi_nilai_instalasi'] = $nilai2;
                        $teknisi['teknisi_note'] = $kata;

                        $cek_inv = Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->first();
                        if ($cek_inv) {
                            $inv['inv_id'] = $cek_inv->inv_id;
                            $sub_inv['subinvoice_id'] = $inv['inv_id'];
                            Invoice::where('inv_idpel', $inv['inv_idpel'])->where('inv_status', 'UNPAID')->update($inv);
                            SubInvoice::where('subinvoice_id', $sub_inv['subinvoice_id'])->update($sub_inv);
                        } else {
                            $inv['inv_id'] = rand(10000, 19999);
                            $sub_inv['subinvoice_id'] = $inv['inv_id'];
                            Invoice::create($inv);
                            SubInvoice::create($sub_inv);
                        }
                        Registrasi::where('reg_idpel', $id)->update($pelanggan);
                        Teknisi::where('teknisi_idpel', $id)->where('teknisi_status', '1')->where('teknisi_userid', $id_teknisi)->update($teknisi);
                        SubBarang::where('id_subbarang', $request->kode)->update($update_barang);


                        $pesan_group['ket'] = 'aktivasi psb';
                        $pesan_group['status'] = '0';
                        $pesan_group['target'] = '120363028776966861@g.us';
                        $pesan_group['nama'] = 'GROUP TEKNISI OVALL';
                        $pesan_group['pesan'] = '               -- PSB SELESAI --

Pemasangan telah selesai dikerjakan  😊


Pelanggan : ' . $query->input_nama . '
Alamat : ' . $query->input_alamat_pasang .
                            '
Teknisi Team : ' . $query->teknisi_team . '
FAT OPM : ' . $request->fat_opm . '
Home OPM : ' . $request->home_opm . '
Los OPM : ' . $request->los_opm . '

Kode Kabel : ' . $request->kode . '
Before Kabel : ' . $request->before . '
after Kabel : ' . $request->after . '
Panjang Kabel : ' . $request->total . '

Waktu Selesai : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
Waktu Pengerjaan : ' . date('H:i', mktime(0, $totalminutes)) . '

Diaktivasi Oleh : ' . $teknisi_nama . '
';


                        Pesan::create($pesan_group);

                        $notifikasi = array(
                            'pesan' => 'Aktivasi Berhasil ',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.teknisi.index')->with($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Pelanggan tidak ditemukan pada Router ' . $query->router_nama,
                            'alert' => 'error',
                        );
                        return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
                    }
                } else {
                    $notifikasi = array(
                        'pesan' => 'Router Discconect',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.teknisi.aktivasi', ['id' => $id])->with($notifikasi);
                }
            }
        }
    }
    public function getBarang(Request $request, $kode)
    {
        $data['q'] = $request->kode_kabel;
        $id_subbarang = SubBarang::where('id_subbarang', $kode)->first();
        return response()->json($id_subbarang);
    }

    public function close_tiket(Request $request, $id)
    {
        $tanggal = date('d M Y H:m:s', strtotime(Carbon::now()));
        $teknisi_id = Auth::user()->id;
        $query = InputData::select('registrasis.*', 'teknisis.*', 'input_data.*', 'tikets.*', 'tikets.created_at as tgl_dibuat')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('teknisis', 'teknisis.teknisi_idpel', '=', 'input_data.id')
            ->join('tikets', 'tikets.tiket_idpel', '=', 'input_data.id')
            ->where('tikets.tiket_id', '=', $id)
            ->where('teknisis.teknisi_userid', '=', $teknisi_id);
        $tiket = $query->first();
        // dd($id);
        if ($request->kode_adaptor) {
            $update_adaptor['subbarang_status'] = '1';
            $update_adaptor['subbarang_keluar'] = '1';
            $update_adaptor['subbarang_stok'] = '0';
            $update_adaptor['subbarang_keterangan'] = ' Ganti Pactcore ' . $tiket->input_nama;

            SubBarang::where('id_subbarang', $request->kode_adaptor)->update($update_adaptor);
        }
        if ($request->kode_pactcore) {
            $update_pactcore['subbarang_status'] = '1';
            $update_pactcore['subbarang_keluar'] = '1';
            $update_pactcore['subbarang_stok'] = '0';
            $update_pactcore['subbarang_keterangan'] = ' Ganti Pactcore ' . $tiket->input_nama;

            SubBarang::where('id_subbarang', $request->kode_pactcore)->update($update_pactcore);
        }
        if ($request->kode_ont) {

            if ($request->alasan == 'Rusak') {
                $data['reg_sn'] = $request->edit_reg_sn;
                $data['reg_mac'] = $request->edit_reg_mac;
                $data['reg_mrek'] = $request->edit_reg_mrek;
                $data['reg_kode_ont'] = $request->edit_reg_kode_ont;
                $update_barang['subbarang_status'] = '1';
                $update_barang['subbarang_keluar'] = '1';
                $update_barang['subbarang_stok'] = '0';
                $update_barang['subbarang_keterangan'] = 'Ganti ONT ' . $request->kode_ont_lama . ' Pel. ' . $tiket->input_nama . ' Karna Rusak. ( ' . $request->keterangan . ' )';

                $update_barang_lama['subbarang_keterangan'] = $request->alasan . ' ' . $request->keterangan;
                $update_barang_lama['subbarang_stok'] = '0';
                $update_barang_lama['subbarang_status'] = '1';
                $update_barang_lama['subbarang_keluar'] = '1';
                Registrasi::where('reg_idpel', $tiket->reg_idpel)->update($data);
                SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
                SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
            } else if ($request->alasan == 'Tukar') {
                $data['reg_sn'] = $request->edit_reg_sn;
                $data['reg_mac'] = $request->edit_reg_mac;
                $data['reg_mrek'] = $request->edit_reg_mrek;
                $data['reg_kode_ont'] = $request->edit_reg_kode_ont;
                $update_barang['subbarang_status'] = '1';
                $update_barang['subbarang_keluar'] = '1';
                $update_barang['subbarang_stok'] = '0';
                $update_barang['subbarang_keterangan'] = 'Tukar ONT ' . $request->kode_ont_lama . ' Pel. ' . $tiket->input_nama . '. ( ' . $request->keterangan . ' )';
                $update_barang_lama['subbarang_status'] = '0';
                $update_barang_lama['subbarang_keluar'] = '0';
                $update_barang_lama['subbarang_stok'] = '1';
                $update_barang_lama['subbarang_keterangan'] = $request->alasan . ' ' . $request->keterangan;
                Registrasi::where('reg_idpel', $tiket->reg_idpel)->update($data);
                SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
                SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
            } else if ($request->alasan == 'Upgrade') {
                $data['reg_sn'] = $request->edit_reg_sn;
                $data['reg_mac'] = $request->edit_reg_mac;
                $data['reg_mrek'] = $request->edit_reg_mrek;
                $data['reg_kode_ont'] = $request->edit_reg_kode_ont;
                $update_barang['subbarang_status'] = '1';
                $update_barang['subbarang_keluar'] = '1';
                $update_barang['subbarang_stok'] = '0';

                $update_barang['subbarang_keterangan'] = 'Upgrade ONT ' . $request->kode_ont_lama . ' Pel. ' . $tiket->input_nama . '. ( ' . $request->keterangan . ' )';
                $update_barang_lama['subbarang_status'] = '0';
                $update_barang_lama['subbarang_keluar'] = '0';
                $update_barang_lama['subbarang_stok'] = '1';

                $update_barang_lama['subbarang_keterangan'] = $request->alasan . ' ' . $request->keterangan;
                Registrasi::where('reg_idpel', $tiket->reg_idpel)->update($data);
                SubBarang::where('id_subbarang', $request->kode_ont_lama)->update($update_barang_lama);
                SubBarang::where('id_subbarang', $request->kode_ont)->update($update_barang);
            }
        }

        $update['tiket_tindakan'] = $request->edit_keterangan;
        $update['tiket_status'] = 'DONE';
        Tiket::where('tiket_id', $id)->update($update);
        $updates['subtiket_id'] = $id;
        $updates['subtiket_status'] = 'DONE';
        $updates['subtiket_admin'] = $teknisi_id;
        $updates['subtiket_teknisi_team'] = $tiket->teknisi_team;
        $updates['subtiket_deskripsi'] = 'Menyelesaikan Tiket';
        SubTiket::create($updates);

        $teknisi['teknisi_job_selesai'] = strtotime(Carbon::now());
        #NILAI TEKNISI
        $waktu_kerja = Teknisi::where('teknisi_idpel', $tiket->reg_idpel)->where('teknisi_job', 'TIKET')->where('teknisi_status', '1')->where('teknisi_userid', $teknisi_id)->first();

        $awal  = $waktu_kerja->teknisi_id;
        $akhir  = $teknisi['teknisi_job_selesai'];

        $diff  = $akhir - $awal;
        if ($diff > 10800) {
            $nilai = '25';
            $kata = '
"Manusia itu memiliki potensi dan kesempatan yang sama pula. Maka jangan menyerah untuk terus berusaha mendapatkan yang terbaik"';
        } elseif ($diff > 7200 & $diff < 10800) {
            $nilai = '50';
            $kata = '
"Hanya karena belum ada yang berhasil melakukannya, bukan berarti kamu tidak mungkin mencapainya"';
        } elseif ($diff > 3600 & $diff < 7200) {
            $nilai = '75';
            $kata = '
"Kami sangat berterima kasih atas dedikasi dan upaya Anda yang tiada henti untuk unggul dalam pekerjaan.  Kami harap Anda terus menginspirasi dan melambung lebih tinggi"';
        } elseif ($diff < 3600) {
            $nilai = '100';
            $kata = '
"Kinerja luar biasa Anda di tempat kerja merupakan inspirasi bagi semua orang dan kami sangat terkesan dan bangga. Pertahankan kerja bagus Anda!"';
        }

        $teknisi['teknisi_waktu_kerja'] = $diff;
        $teknisi['teknisi_nilai'] = $nilai;
        $teknisi['teknisi_note'] = $kata;

        Teknisi::where('teknisi_idpel', $tiket->reg_idpel)->where('teknisi_job', 'TIKET')->where('teknisi_status', '1')->where('teknisi_userid', $teknisi_id)->update($teknisi);


        $pesan_group['ket'] = 'close tiket';
        $pesan_group['status'] = '0';
        $pesan_group['target'] = '120363028776966861@g.us';
        $pesan_group['nama'] = 'GROUP TEKNISI OVALL';
        $pesan_group['pesan'] = '               -- CLOSE TIKET --

Hallo Broo..  
Terimakasih tiket sudah anda selesaikan  😊

Notiket : *' . $id . '*
Topik : ' . $tiket->tiket_judul . '
Tindakan : *' . $request->edit_keterangan . '*

Teknisi : ' . $tiket->teknisi_team . '

Pelanggan : ' . $tiket->input_nama . '
Alamat : ' . $tiket->input_alamat_pasang . '

Tanggal Selesai : ' . $tanggal . '
';

        $pesan_pelanggan['ket'] = 'close tiket';
        $pesan_pelanggan['status'] = '0';
        $pesan_pelanggan['target'] = $tiket->input_hp;
        $pesan_group['nama'] = $tiket->input_nama;
        $pesan_pelanggan['pesan'] = '               -- CLOSE TIKET --

Pelanggan yth
Terimaksi kasih atas kepercayan anda menggunakan layanan kami.
Saat ini gangguan telah selesai ditangani.

Nomor tiket : *' . $id . '* 
Topik : ' . $tiket->tiket_judul . '
Tindakan : *' . $request->edit_keterangan . '*

Tanggal Selesai : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '

Tiket Laporan anda akan kami proses secepat mungkin, pastikan nomor anda selalu aktif agar bisa di hubungi kembali.
Terima kasih.';

        // dd($pesan_pelanggan);
        Pesan::create($pesan_group);
        Pesan::create($pesan_pelanggan);


        $notifikasi = [
            'pesan' => 'Berhasil mengambil job',
            'alert' => 'success',
        ];
        // dd('berhasil');
        return redirect()->route('admin.teknisi.index')->with($notifikasi);
    }
}
