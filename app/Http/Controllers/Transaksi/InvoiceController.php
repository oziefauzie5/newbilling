<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Applikasi\SettingWhatsapp;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\Paid;
use App\Models\Transaksi\SubInvoice;
use App\Models\Transaksi\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $pasang_bulan_ini = Carbon::now()->addMonth(-0)->format('Y-m-d');
        $pasang_bulan_lalu = Carbon::now()->addMonth(-1)->format('Y-m-d');
        $pasang_3_bulan_lalu = Carbon::now()->addMonth(-2)->format('Y-m-d');
        // dd($pasang_3_bulan_lalu);
        $month = Carbon::now()->format('m');
        $data['data_bulan'] = $request->query('data_bulan');
        $data['bulan'] = $request->query('bulan');
        // dd($data['bulan']);
        $data['data_inv'] = $request->query('data_inv');
        $data['q'] = $request->query('q');
        // dd($data['data_bulan']);

        $query = Invoice::where('inv_status', '!=', 'PAID')
            ->orderBy('inv_id', 'ASC')
            // ->orderBy('inv_tgl_jatuh_tempo', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('inv_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query->whereMonth('inv_tgl_jatuh_tempo', date('m', strtotime($data['bulan'])))->whereYear('inv_tgl_jatuh_tempo', date('Y', strtotime($data['bulan'])));


        if ($data['data_bulan'] == "1") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_ini);
            $data['data_bulan'] = 'PELANGGAN BARU';
        } elseif ($data['data_bulan'] == "2") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_lalu);
            $data['data_bulan'] = 'PELANGGAN 2 BULAN';
        } elseif ($data['data_bulan'] == "3") {
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_3_bulan_lalu);
            $data['data_bulan'] = 'PELANGGAN 3 BULAN';
        }


        if ($data['data_inv'])
            $query->where('inv_status', '=', $data['data_inv']);
        // $export = $query->get();
        //         foreach ($export as $value) {
        //             echo '<table><tr><td>'.$value->inv_nolayanan.'</td><td>'.$value->inv_nama.'</td><td>'.$value->inv_id.'</td><td>'.$value->inv_tgl_jatuh_tempo.'</td><td>'.$value->inv_tgl_isolir.'</td></tr></table>';
        //         }
        //         dd('test');


        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->paginate(20);
        $data['inv_count_belum_terbayar'] = Invoice::where('inv_status', '!=', 'PAID')->count() + Invoice::where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '=', $month)->count();
        $data['inv_count_total'] = Invoice::whereMonth('inv_tgl_jatuh_tempo', '=', $month)->count();
        $data['inv_count_unpaid'] = Invoice::where('inv_status', '=', 'UNPAID')->count();
        $data['inv_count_lunas'] = Invoice::where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '=', $month)->count();
        $data['inv_belum_lunas'] = Invoice::where('inv_status', '!=', 'PAID')->sum('inv_total');
        $data['inv_lunas'] = Invoice::where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '=', $month)->sum('inv_total');
        $data['inv_count_suspend'] = Invoice::where('inv_status', '=', 'SUSPEND')->count();
        $data['inv_count_isolir'] = Invoice::where('inv_status', '=', 'ISOLIR')->count();
        return view('Transaksi/list_invoice', $data);
    }
    public function paid(Request $request)
    {
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->addMonth(-0)->format('m');
        $data['q'] = $request->query('q');
        $data['bulan'] = $request->query('bulan');
        $data['tglbayar'] = $request->query('tglbayar');
        $invoice = Invoice::where('invoices.inv_status', '=', 'PAID')
            // ->where('invoices.inv_jenis_tagihan', '!=', 'FREE')
            // ->whereMonth('invoices.inv_tgl_bayar', '=', $month)
            ->orderBy('inv_tgl_bayar', 'DESC')
            ->where(function ($invoice) use ($data) {
                $invoice->orWhere('inv_id', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('inv_nama', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('inv_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
                // $invoice->orWhere('inv_admin', 'like', '%' . $data['q'] . '%')->Where('inv_cabar', 'TRANSFER');
                $invoice->orWhere('inv_jenis_tagihan', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $invoice->whereMonth('inv_tgl_jatuh_tempo', date('m', strtotime($data['bulan'])))->whereYear('inv_tgl_jatuh_tempo', date('Y', strtotime($data['bulan'])));

        if ($data['tglbayar'])
            $invoice->whereDate('inv_tgl_bayar', $data['tglbayar']);

        // $export = $invoice->get();
        // foreach ($export as $value) {
        //     echo '<table><tr><td>'.$value->inv_nolayanan.'</td><td>'.$value->inv_nama.'</td><td>'.$value->inv_tgl_jatuh_tempo.'</td><td>https://ovallapp.com/storage/bukti-transfer/'.$value->inv_bukti_bayar.'</td></tr></table>';
        // }
        // dd('test');
        $data['data_invoice'] = $invoice->paginate(10);
        $data['inv_count_bulan'] = $invoice->count();
        // $data['inv_bulan'] = $invoice->sum('inv_total');
        $data['inv_bulan'] = $invoice->sum('inv_total');
        $data['inv_hari'] = $invoice->whereDay('inv_tgl_bayar', '=', $day)->sum('inv_total');
        return view('Transaksi/list_invoice_paid', $data);
    }
    public function delete_inv($inv_id)
    {

        $invoice = Invoice::where('inv_id', '=', $inv_id)->first();
        $sub_invoice = SubInvoice::where('subinvoice_id', '=', $inv_id)->first();
        // dd($invoice);
        if ($sub_invoice) {
            $sub_invoice->delete();
        }
        if ($invoice) {
            $invoice->delete();
        }

        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data',
            'alert' => 'success',
        ];
        // return redirect()->route('admin.inv.list_invoice_paid')->with($notifikasi);
        return redirect()->route('admin.inv.paid');
        // return view('Transaksi/list_invoice_paid', $data);

    }

    public function sub_invoice($id)
    {
        $data['invoice'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            // ->join('users', 'users.id', '=', 'invoices.inv_admin')
            ->where('inv_id', $id)->first();

        if ($data['invoice']->inv_admin) {
            if ($data['invoice']->inv_admin == 'SYSTEM') {
                $data['nama_admin'] = '';
            } else {
                $admin = User::where('id', $data['invoice']->inv_admin)->first();
                $data['nama_admin'] = $admin->name;
            }
        } else {
            $data['nama_admin'] = '';
        }

        // dd($data['invoice']);sss

        $data['deskripsi'] = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->where('invoices.inv_id', $id)->get();


        $data['sumharga'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_total');
        $data['sumppn'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn');
        $data['ppnj'] = env('PPN');
        $data['akun'] = $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'LAPORAN')->get();
        $data['ppn'] = SettingBiaya::first();


        return view('Transaksi/subinvoice', $data);
    }
    public function print_inv(Request $request, $id)
    {



        if ($request->cara_print == 1) {
            $admin_user = Auth::user()->id;
            $data['admin'] = Auth::user()->name;
            $data['invoice'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
                ->where('inv_id', $id)->first();
            $data['deskripsi'] = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
                ->where('invoices.inv_id', $id)->get();



            if ($data['invoice']->inv_admin) {
                if ($data['invoice']->inv_admin == 'SYSTEM') {
                    $data['nama_admin'] = '';
                } else {
                    $admin = User::where('id', $data['invoice']->inv_admin)->first();
                    $data['nama_admin'] = $admin->name;
                }
            } else {
                $data['nama_admin'] = '';
            }


            $data['sumharga'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_total');
            $data['sumppn'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn');
            $data['ppnj'] = env('PPN');
            $data['akun'] = $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'LAPORAN')->get();
            $data['ppn'] = SettingBiaya::first();
            // return view('Transaksi/print_thermal', $data);

            $pdf = App::make('dompdf.wrapper');
            $html = view('Transaksi/print_thermal', $data)->render();
            $pdf->loadHTML($html);
            // $pdf->setPaper([ 0 , 0 , 3211,02 , 3211,02 ], 'potrait');
            // $pdf->set_option('dpi', 72);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->download('invoice.pdf');
        } else {

            $data['invoice'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
                ->where('inv_id', $id)->first();
            $data['deskripsi'] = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
                ->where('invoices.inv_id', $id)->get();



            if ($data['invoice']->inv_admin) {
                if ($data['invoice']->inv_admin == 'SYSTEM') {
                    $data['nama_admin'] = '';
                } else {
                    $admin = User::where('id', $data['invoice']->inv_admin)->first();
                    $data['nama_admin'] = $admin->name;
                }
            } else {
                $data['nama_admin'] = '';
            }


            $data['sumharga'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_total');
            $data['sumppn'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn');
            $data['ppnj'] = env('PPN');
            $data['akun'] = $data['setting_akun'] = (new GlobalController)->setting_akun()->where('akun_kategori', '!=', 'LAPORAN')->get();
            $data['ppn'] = SettingBiaya::first();


            return view('Transaksi/print_subinvoice', $data);
        }
    }
    public function addDiskon(Request $request, $id)
    {
        // return response()->json($id);
        $data['inv_diskon'] = $request->diskon;
        $data['inv_total'] = $request->total;
        Invoice::where('inv_id', $id)->update($data);
        return response()->json($data);
    }

    public function rollback(Request $request, $id)
    {

        // $data_laporan = Laporan::where('lap_inv',$id)->first();
        // if($data_laporan->)
        $swaktu = SettingWaktuTagihan::first();
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $admin_user = Auth::user()->id;
        $tampil = DB::table('invoices')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_id', '=', $id)
            ->orWhere('inv_nolayanan', '=', $id)
            ->first();
        // dd( $tampil);
        $diskon = $tampil->inv_diskon;

        $sumppn = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn'); #hitung total ppn invoice
        $sumharga = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_harga'); #hitung total harga invoice
        $total_debet = $sumharga + $sumppn - $diskon;

        #Kembalikan tanggal jatuh tempo ke tanggal sebelumnya
        $inv_tgl_isolir = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
        $inv_tgl_tagih = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addDay(-$swaktu->wt_jeda_isolir_hari)->toDateString();
        $kurangi_tgl_jth_tempo = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addMonth(-1)->toDateString();

        // dd($tampil->inv_tgl_jatuh_tempo);


        $data_lap['lap_id'] = 0;
        $data_lap['lap_tgl'] = $tgl_bayar;

        $data_lap['lap_admin'] = $admin_user;
        $data_lap['lap_cabar'] = $tampil->inv_cabar;
        $data_lap['lap_kredit'] = 0;
        $data_lap['lap_debet'] = $tampil->inv_total;
        $data_lap['lap_adm'] = 0;
        $data_lap['lap_jumlah_bayar'] = 0;
        $data_lap['lap_keterangan'] = 'Rollback Invoice ' . $tampil->inv_id . ' ( ' . $tampil->inv_nama . ' )';
        $data_lap['lap_akun'] = $tampil->inv_akun;
        $data_lap['lap_idpel'] = $tampil->inv_idpel;
        $data_lap['lap_jenis_inv'] = "PENGEMBALIAN SALDO";
        $data_lap['lap_status'] = 0;
        $data_lap['lap_img'] = "-";
        Laporan::create($data_lap);

        $update_lap['lap_inv'] = '';
        Laporan::where('lap_inv', $id)->update($update_lap);

        $cek_role = DB::table('users')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', $tampil->inv_admin)
            ->first();

        if ($cek_role->role_id >= '10' && $cek_role->role_id <= '13') {

            $saldo = (new GlobalController)->total_mutasi($tampil->inv_admin);
            $total = $saldo + $total_debet; #SALDO MUTASI = DEBET - KREDIT
            $biller = MitraSetting::where('mts_user_id', $tampil->inv_admin)->first(); #mengambil biaya admni biller pada table mitra_setting
            $pengurangan_adm = -$biller->mts_komisi;

            Mutasi::create([
                'mt_admin' => $admin_user,
                'mt_mts_id' => $tampil->inv_admin,
                'mt_kategori' => 'ROLLBACK',
                'mt_cabar' => '2',
                'mt_deskripsi' => 'Rollback Invoice ' . $tampil->inv_id . ' ( ' . $tampil->inv_nama . ' ) ',
                'mt_kredit' => $total_debet,
                'mt_saldo' => $total,
                'mt_biaya_adm' => $pengurangan_adm,
            ]);
        }

        if ($tgl_bayar >= $tampil->inv_tgl_jatuh_tempo) {
            $status = 'SUSPEND';
        } elseif ($tgl_bayar < $tampil->inv_tgl_jatuh_tempo) {
            $status = 'UNPAID';
            $router = Router::whereId($tampil->reg_router)->first();
            $ip =   $router->router_ip . ':' . $router->router_port_api;
            $user = $router->router_username;
            $pass = $router->router_password;
            $API = new RouterosAPI();
            $API->debug = false;

            if ($API->connect($ip, $user, $pass)) {
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $tampil->reg_username,
                ]);
                if ($cek_secret) {
                    $API->comm('/ppp/secret/set', [
                        '.id' => $cek_secret[0]['.id'],
                        'profile' =>  $tampil->paket_nama == '' ? '' : $tampil->paket_nama,
                        'comment' => 'By: ROLBACK -' . $kurangi_tgl_jth_tempo == '' ? '' : 'By: ROLBACK -' . $kurangi_tgl_jth_tempo,
                        'disabled' => 'no',
                    ]);
                } else {
                    $API->comm('/ppp/secret/add', [
                        'name' => $tampil->reg_username == '' ? '' : $tampil->reg_username,
                        'password' => $tampil->reg_password  == '' ? '' : $tampil->reg_password,
                        'service' => 'pppoe',
                        'profile' => $tampil->paket_nama  == '' ? 'default' : $tampil->paket_nama,
                        'comment' => 'By: ROLBACK -' . $kurangi_tgl_jth_tempo == '' ? '' : 'By: ROLBACK -' . $kurangi_tgl_jth_tempo,
                        'disabled' => 'no',
                    ]);
                }
            }
        }

        $data['inv_akun'] = '0';
        $data['inv_cabar'] = '';
        $data['inv_tgl_bayar'] = '';
        $data['inv_status'] = $status;
        $data['inv_tgl_isolir'] = $inv_tgl_isolir;
        $data['inv_tgl_tagih'] = $inv_tgl_tagih;
        $data['inv_tgl_jatuh_tempo'] = $tampil->inv_tgl_jatuh_tempo;
        Invoice::where('inv_id', $id)->update($data);


        Registrasi::where('reg_idpel', $tampil->inv_idpel)->update([
            'reg_status' => $status,
            'reg_tgl_jatuh_tempo' => $kurangi_tgl_jth_tempo,
        ]);

        // dd($tampil->inv_idpel);
        $notifikasi = array(
            'pesan' => 'Berhasil melakukan Rollback',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $tampil->inv_id])->with($notifikasi);
    }

    public function payment(Request $request, $id)

    {



        $nama_user = Auth::user()->name; #NAMA USER
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $sum_trx = Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->sum('trx_total');
        $count_trx = Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->sum('trx_qty');


        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        $cek_inv = Laporan::where('lap_inv', $id)->first();
        if ($cek_inv) {
            $notifikasi = array(
                'pesan' => 'Invoice telah terbayar pada laporan admin',
                'alert' => 'error',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
        } else {
            $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
                ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
                ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                ->where('inv_id', $id)
                ->first();

            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran

            //          // HITUNG TELAT PEMBAYARAN
            // $date1 = Carbon::createFromDate($data_pelanggan->reg_tgl_jatuh_tempo); // start date
            // $valid_date = Carbon::parse($date1)->toDateString();
            // $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
            // $today = new \DateTime();
            // $today->setTime(0, 0, 0);

            // $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
            // $match_date->setTime(0, 0, 0);

            // $diff = $today->diff($match_date);
            // $diffDays = (int)$diff->format("%R%a");

            // // dd($diffDays);
            // // ------------------------

            // if ($diffDays < -0) {
            //     dd($diffDays.' SUSPEND');
            // } else{
            //     dd($diffDays.' UNPAID');
            // } 
            // #JIKA PEMBAYARAN TELAT 5 HARI. JATUH TEMPO TIDAK BERUBAH

            // dd($diffDays);
            // BATA AKHIR HITUNG TELAT PEMBAYARAN


            $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo)); #new
            $hari_tgl_tagih = date('d', strtotime($data_pelanggan->reg_tgl_tagih)); #new

            $inv0_tagih = Carbon::create($year . '-' . $month . '-' . $hari_tgl_tagih)->addMonth(1)->toDateString(); #new
            $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
            $inv0_jt_tempo = Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString(); #new
            // dd($inv0_tagih);

            $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
            $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
            $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
            if ($data_pelanggan->reg_inv_control == 0) {
                $reg['reg_tgl_jatuh_tempo'] = $inv0_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv0_tagih0;
            } else {
                $reg['reg_tgl_jatuh_tempo'] = $inv1_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv1_tagih1;
            }
            $admin_user = Auth::user()->id;
            $admin_nama = Auth::user()->name;

            $explode = explode('|', $request->transfer);
            if ($request->cabar == 'TUNAI') {
                $akun = '2';
                $norek = '-';
                $akun_nama = 'TUNAI';
                $biaya_adm =  0;
                $j_bayar = 0;
                $filename = "";
            } elseif ($request->cabar == 'TRANSFER') {
                $akun = $explode[0];
                $norek = $explode[1];
                $akun_nama = $explode[2];
                $biaya_adm =  $request->jumlah_bayar - $data_pelanggan->inv_total;
                $j_bayar = $request->jumlah_bayar;
                $file = $request->file('inv_bukti_bayar');
                $filename = date('d-m-Y', strtotime(Carbon::now())) . '-' . str_replace(" ", "-", $data_pelanggan->inv_nama) . '.jpeg';
                $path = 'bukti-transfer/' . $filename;
                Storage::disk('public')->put($path, file_get_contents($file));
            }


            $datas['inv_cabar'] = $request->cabar;
            $datas['inv_admin'] = $admin_user;
            $datas['inv_akun'] = $akun;
            $datas['inv_reference'] = '-';
            $datas['inv_payment_method'] = $akun_nama;
            $datas['inv_payment_method_code'] = $norek;
            $datas['inv_total_amount'] = $data_pelanggan->inv_total;
            $datas['inv_fee_merchant'] = 0;
            $datas['inv_fee_customer'] = 0;
            $datas['inv_total_fee'] = 0;
            $datas['inv_amount_received'] = 0;
            $datas['inv_tgl_bayar'] = $tgl_bayar;
            $datas['inv_bukti_bayar'] = $filename;
            $datas['inv_status'] = 'PAID';
            Invoice::where('inv_id', $id)->update($datas);

            $data_lap['lap_id'] = time();
            $data_lap['lap_tgl'] = $tgl_bayar;
            $data_lap['lap_inv'] = $id;
            $data_lap['lap_admin'] = $admin_user;
            $data_lap['lap_cabar'] = $request->cabar;
            $data_lap['lap_debet'] = 0;
            $data_lap['lap_kredit'] = $data_pelanggan->inv_total;
            $data_lap['lap_adm'] = $biaya_adm;
            $data_lap['lap_jumlah_bayar'] = $j_bayar;
            $data_lap['lap_keterangan'] = $data_pelanggan->inv_nama;
            $data_lap['lap_akun'] = $akun;
            $data_lap['lap_idpel'] = $data_pelanggan->inv_idpel;
            $data_lap['lap_jenis_inv'] = "INVOICE";
            $data_lap['lap_status'] = 0;
            $data_lap['lap_img'] = "-";

            Laporan::create($data_lap);
            $reg['reg_status'] = 'PAID';
            Registrasi::where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);

            if ($count_trx == 0) {
                $data_trx['trx_kategori'] = 'PEMASUKAN';
                $data_trx['trx_jenis'] = 'INVOICE';
                $data_trx['trx_admin'] = 'SYSTEM';
                $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                $data_trx['trx_qty'] = 1;
                $data_trx['trx_total'] = $data_pelanggan->inv_total;
                Transaksi::where('trx_jenis', 'INVOICE')->create($data_trx);
            } else {
                $i = '1';
                $data_trx['trx_qty'] = $count_trx + $i;
                $data_trx['trx_total'] = $sum_trx + $data_pelanggan->inv_total;
                Transaksi::where('trx_jenis', 'INVOICE')->whereDate('created_at', $tgl_bayar)->update($data_trx);
            }

            $status = (new GlobalController)->whatsapp_status();

            if ($status->wa_status == 'Enable') {
                $pesan_group['status'] = '0';
            } else {
                $pesan_group['status'] = '10';
            }

            $pesan_group['ket'] = 'payment';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['nama'] = $data_pelanggan->input_nama;
            $pesan_group['pesan'] = '
Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $data_pelanggan->inv_nolayanan . '
Pelanggan : ' . $data_pelanggan->inv_nama . '
Invoice : *' . $data_pelanggan->inv_id . '*
Paket : ' . $data_pelanggan->inv_profile . '
Total : *Rp' . $data_pelanggan->inv_total . '*

Tanggal lunas : ' . date('d-m-Y H:m:s', strtotime(Carbon::now())) . '
Layanan sudah aktif dan dapat digunakan sampai dengan *' . $reg['reg_tgl_jatuh_tempo'] . '*

BY : ' . $nama_user . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*';
            Pesan::create($pesan_group);

            $router = Router::whereId($data_pelanggan->reg_router)->first();
            $ip =   $router->router_ip . ':' . $router->router_port_api;
            $user = $router->router_username;
            $pass = $router->router_password;
            $API = new RouterosAPI();
            $API->debug = false;

            if ($API->connect($ip, $user, $pass)) {
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $data_pelanggan->reg_username,
                ]);
                if ($cek_secret) {
                    $API->comm('/ppp/secret/set', [
                        '.id' => $cek_secret[0]['.id'],
                        'profile' =>  $data_pelanggan->paket_nama == '' ? '' : $data_pelanggan->paket_nama,
                        'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],


                        'disabled' => 'no',
                    ]);
                    $cek_status = $API->comm('/ppp/active/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_status) {
                        $API->comm('/ppp/active/remove', [
                            '.id' =>  $cek_status['0']['.id'],
                        ]);
                        $notifikasi = array(
                            'pesan' => 'Berhasil melakukan pembayaran',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
                    } else {
                        $notifikasi = array(
                            'pesan' => 'Berhasil melakukan pembayaran. Namun pelanggan sedang tidak aktif',
                            'alert' => 'success',
                        );
                        return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
                    }
                } else {
                    $API->comm('/ppp/secret/add', [
                        'name' => $data_pelanggan->reg_username == '' ? '' : $data_pelanggan->reg_username,
                        'password' => $data_pelanggan->reg_password  == '' ? '' : $data_pelanggan->reg_password,
                        'service' => 'pppoe',
                        'profile' => $data_pelanggan->paket_nama  == '' ? 'default' : $data_pelanggan->paket_nama,
                        'comment' => 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'] == '' ? '' : 'By:' . $nama_user . '-' . $reg['reg_tgl_jatuh_tempo'],
                        'disabled' => 'no',
                    ]);

                    $notifikasi = array(
                        'pesan' => 'Berhasil melakukan pembayaran',
                        'alert' => 'success',
                    );
                    return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
                }
            } else {
                $notifikasi = array(
                    'pesan' => 'Berhasil melakukan pembayaran. Namun Maaf..!! Router Disconnected',
                    'alert' => 'success',
                );
                return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
            }
        }
    }
    public function addons(Request $request, $id)
    {
        $unp = Invoice::where('inv_id', $id)->first();
        if ($unp) {

            $data['subinvoice_id'] = $id;
            $data['subinvoice_deskripsi'] = $request->Deskripsi;
            $data['subinvoice_qty'] = $request->qty;
            $data['subinvoice_harga'] = $request->harga;
            $data['subinvoice_ppn'] = $request->ppn;
            $data['subinvoice_total'] = $request->total;
            $data['subinvoice_status'] = '1';
            $upd['inv_total'] = $unp->inv_total + $request->total;
            SubInvoice::create($data);
            Invoice::where('inv_id', $id)->update($upd);
            $notifikasi = array(
                'pesan' => 'Berhasil menambahkan addons',
                'alert' => 'success',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
        } else {
            $notifikasi = array(
                'pesan' => 'Gagal menambahkan addons',
                'alert' => 'error',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
        }
    }

    public function addons_delete($id, $inv, $tot)
    {
        $unp = Invoice::where('inv_id', $inv)->first();
        $upd['inv_total'] = $unp->inv_total - $tot;
        Invoice::where('inv_id', $inv)->update($upd);
        SubInvoice::where('id', $id)->delete();
        $notifikasi = array(
            'pesan' => 'Berhasil menghapus addons',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $inv])->with($notifikasi);
    }
    public function update_inv(Request $request, $inv_id)
    {
        $data_pelanggan = Invoice::where('inv_id', '=', $inv_id)
            ->first();
        $swaktu = SettingWaktuTagihan::first();
        $tgl_tagih =  Carbon::create($request->tgl_jth_tempo)->addDay(-$swaktu->wt_jeda_tagihan_pertama)->toDateString();
        $periode = date('d-m-Y', strtotime(Carbon::create($request->tgl_jth_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($request->tgl_jth_tempo)->addMonth(1)->toDateString()));

        $cek_hari = date('d', strtotime($request->tgl_jth_tempo));
        if ($cek_hari == 31) {
            $jeda_waktu = '0';
        } elseif ($cek_hari == 30) {
            $jeda_waktu = '0';
        } else {
            $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
        }
        $tgl_isolir =  Carbon::create($request->tgl_jth_tempo)->addDay($jeda_waktu)->toDateString();
        // dd($data_pelanggan->inv_profile);

        Invoice::where('inv_id', '=', $inv_id)->update([
            'inv_tgl_tagih' => $tgl_tagih,
            'inv_tgl_jatuh_tempo' => date('Y-m-d', strtotime($request->tgl_jth_tempo)),
            'inv_tgl_isolir' => $tgl_isolir,
            'inv_periode' => $periode,
        ]);

        SubInvoice::where('subinvoice_id', '=', $inv_id)->update(
            [
                'subinvoice_id' => $inv_id,
                'subinvoice_deskripsi' => $data_pelanggan->inv_profile . ' ( ' . $periode . ' )',
            ]
        );
        $notifikasi = array(
            'pesan' => 'Berhasil merubah tanggal jatuh tempo',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $inv_id])->with($notifikasi);
    }
    public function update_tgl_bayar(Request $request, $inv_id)
    {
        $data_pelanggan = Invoice::where('inv_id', '=', $inv_id)
            ->first();

        Invoice::where('inv_id', '=', $inv_id)->update([
            'inv_tgl_bayar' => date('Y-m-d', strtotime($request->tgl_bayar)),
        ]);

        $notifikasi = array(
            'pesan' => 'Berhasil merubah tanggal bayar',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $inv_id])->with($notifikasi);
    }

    public function add_inv_manual(Request $request)
    {
        dd($request->add_nolayanan);
        $now = Carbon::now();
        $month = $now->format('m');
        $hitung = Invoice::whereMonth('tgl_jatuh_tempo', $month)->counnt();
        $year = $now->format('Y');
        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'reg_profile')
            ->where('reg_nolayanan', '=', 240175402)
            ->first();
        $swaktu = SettingWaktuTagihan::first();
        $i = 1338;
        $inv_id = rand(1000, 1999) . $i++;
        $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo));
        $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($data_pelanggan->reg_tgl_tagih));
        $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
        $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo));
        $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($swaktu->wt_jeda_tagihan_pertama)->toDateString();
        // dd($tgl_isolir);
        Invoice::create([
            'inv_id' => $inv_id,
            'inv_status' => 'UNPAID',
            'inv_idpel' => $data_pelanggan->reg_idpel,
            'inv_nolayanan' => $data_pelanggan->reg_nolayanan,
            'inv_nama' => $data_pelanggan->input_nama,
            'inv_jenis_tagihan' => $data_pelanggan->reg_jenis_tagihan,
            'inv_profile' => $data_pelanggan->paket_nama,
            'inv_mitra' => 'SYSTEM',
            'inv_kategori' => 'OTOMATIS',
            'inv_tgl_tagih' => $hari_tgl_tagih,
            'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
            'inv_tgl_isolir' => $tgl_isolir,
            'inv_periode' => $periode1blan,
            'inv_total' => $data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
        ]);

        SubInvoice::create(
            [
                'subinvoice_id' => $inv_id,
                'subinvoice_deskripsi' => $data_pelanggan->paket_nama . ' ( ' . $periode1blan . ' )',
                'subinvoice_harga' => $data_pelanggan->reg_harga + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
                'subinvoice_ppn' => $data_pelanggan->reg_ppn,
                'subinvoice_total' => $data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
                'subinvoice_qty' => 1,
                'subinvoice_status' => 0,
            ]
        );
    }
    public function test1()
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $month = Carbon::now()->addMonth(-0)->format('m');
        $invoice = Invoice::where('invoices.inv_status', '=', 'PAID')
            ->where('invoices.inv_jenis_tagihan', '=', 'FREE')
            ->whereMonth('invoices.inv_tgl_bayar', '=', $month)
            ->get();

        foreach ($invoice as $key) {
            Invoice::where('inv_id', $key->inv_id)->update(
                [
                    'inv_status' => 'UNPAID',
                ]
            );
            // Laporan::create([
            //     'lap_id'=> time(),
            //     'lap_tgl'=> $data['now'],
            //     'lap_inv'=> $key->inv_id,
            //     'lap_admin'=> $key->inv_admin,
            //     'lap_cabar'=> $key->inv_cabar,
            //     'lap_debet'=> 0,
            //     'lap_kredit'=> $key->inv_total,
            //     'lap_adm'=> $key->inv_total_fee,
            //     'lap_jumlah_bayar'=> $key->inv_total_amount,
            //     'lap_keterangan'=> $key->inv_nama,
            //     'lap_akun'=> $key->inv_akun,
            //     'lap_idpel'=> $key->inv_idpel,
            //     'lap_jenis_inv'=> "INVOICE",
            //     'lap_status'=> 0,
            //     'lap_img'=> "-",
            // ]);
            echo '<table><tr><td>' . $key->inv_status . '</td><td>' . $key->inv_jenis_tagihan . '</td><td>' . $key->inv_id . '</td></tr></table>';
        }
    }
    public function test2()
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->whereDate('inv_tgl_isolir', '<=', $data['now'])
            ->where('inv_status', '!=', 'PAID')
            ->where('inv_jenis_tagihan', '=', 'FREE')
            ->first();

        if ($data_pelanggan) {
            $status = (new GlobalController)->whatsapp_status();
            if ($status->wa_status == 'Enable') {
                $pesan_group['status'] = '0';
            } else {
                $pesan_group['status'] = '10';
            }

            $pesan_group['ket'] = 'isolir otomatis';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['nama'] = $data_pelanggan->input_nama;
            $pesan_group['pesan'] = '
Pelanggan yang terhormat,
Kami informasikan bahwa layanan internet anda saat ini sedang di *ISOLIR* oleh sistem secara otomatis❗, kami mohon maaf atas ketidaknyamanannya
Agar dapat digunakan kembali dimohon untuk melakukan pembayaran tagihan sebagai berikut :

No.Layanan : *' . $data_pelanggan->reg_nolayanan . '*
Pelanggan : ' . $data_pelanggan->inv_nama . '
Invoice : ' . $data_pelanggan->inv_id . '
Jatuh Tempo : ' . $data_pelanggan->reg_tgl_jatuh_tempo . '
Total tagihan :Rp. *' . number_format($data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama) . '*

--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*OVALL FIBER*
';
            Pesan::create($pesan_group);

            $ip =   $data_pelanggan->router_ip . ':' . $data_pelanggan->router_port_api;
            $user = $data_pelanggan->router_username;
            $pass = $data_pelanggan->router_password;
            $API = new RouterosAPI();
            $API->debug = false;
            if ($data_pelanggan->reg_layanan == 'PPP') {
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ppp/secret/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'comment' => 'ISOLIR OTOMATIS' == '' ? '' : 'ISOLIR OTOMATIS',
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
                        $cek_status = $API->comm('/ppp/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ppp/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                    }
                }
            } elseif ($data_pelanggan->reg_layanan == 'HOTSPOT') {
                if ($API->connect($ip, $user, $pass)) {
                    $cek_secret = $API->comm('/ip/hotspot/user/print', [
                        '?name' => $data_pelanggan->reg_username,
                    ]);
                    if ($cek_secret) {
                        $API->comm('/ip/hotspot/user/set', [
                            '.id' => $cek_secret[0]['.id'],
                            'comment' => 'ISOLIR OTOMATIS' == '' ? '' : 'ISOLIR OTOMATIS',
                            'disabled' => 'yes',
                        ]);

                        Invoice::where('inv_id', $data_pelanggan->inv_id)->update([
                            'inv_status' => 'ISOLIR',
                        ]);
                        Registrasi::where('reg_idpel', $data_pelanggan->inv_idpel)->update([
                            'reg_status' => 'ISOLIR',
                        ]);
                        $cek_status = $API->comm('/ip/hotspot/active/print', [
                            '?name' => $data_pelanggan->reg_username,
                        ]);
                        if ($cek_status) {
                            $API->comm('/ip/hotspot/active/remove', [
                                '.id' =>  $cek_status['0']['.id'],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
