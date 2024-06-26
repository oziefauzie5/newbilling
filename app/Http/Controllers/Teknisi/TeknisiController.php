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
use App\Models\Transaksi\Addons;
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
        // $kredit = komisi::where('komisi_user_id', $teknisi_id)->sum('komisi_kredit');
        // $debet = komisi::where('komisi_user_id', $teknisi_id)->sum('komisi_debet');
        // $komisi = $kredit - $debet;

        // 'tittle' => 'Registrasi',
        // 'data_teknisi' => DB::table('registrasis')->join('pelanggans', 'pelanggans.idpel', '=', 'registrasis.id')->where('pelanggans.status', 'Pemasangan')->get(),
        // 'data_teknisi' => Teknisi::all(),
        $data['q'] = $request->query('q');
        $query = InputData::join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('registrasis.reg_progres', '=', '0')
            ->orderBy('input_data.input_tgl', 'DESC');
        // ->where(function ($query) use ($data) {
        //     $query->where('input_nama', 'like', '%' . $data['q'] . '%');
        //     $query->orWhere('input_alamat_pasang', 'like', '%' . $data['q'] . '%');
        // });
        $data['data_pelanggan'] = $query->get();
        $data['data_user'] = DB::table('roles')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'users.id', '=', 'model_has_roles.model_id')
            ->where('roles.name', '=', 'teknisi')
            ->where('users.id', '=', $teknisi_id)
            ->get();

        // $data['komisi'] = $komisi;
        // $data['debet'] = $debet;
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
        // $yesterday = date("d F Y, H:i:s", $id);
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

                    $teknisi['teknisi_waktu_kerja'] = $diff;
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
    public function getBarang(Request $request, $kode)
    {
        $data['q'] = $request->kode_kabel;
        $id_subbarang = SubBarang::where('id_subbarang', $kode)->first();
        return response()->json($id_subbarang);
    }
}
