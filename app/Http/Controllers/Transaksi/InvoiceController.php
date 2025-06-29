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
use App\Models\Mitra\MutasiSales;
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
use App\Models\Tiket\Data_Tiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use App\Exports\ExportInvoice;
use App\Models\Transaksi\Jurnal;
// use App\Imports\ImportUsers;
use App\Models\Gudang\Data_Barang;
use App\Models\Gudang\Data_BarangKeluar;
use Illuminate\Support\Facades\Session;
use App\Models\PSB\FtthFee;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\PSB\ApiController;
use App\Models\PSB\FtthInstalasi;
// use CURLFile;
use App\Models\Aplikasi\Corporate;

class InvoiceController extends Controller
{

    public function suspend_manual()
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $unp = Invoice::where('inv_status', 'UNPAID')->whereDate('inv_tgl_jatuh_tempo', '<=', $data['now'])->get();
        foreach ($unp as $d) {
            Invoice::where('inv_id', $d->inv_id)->update([
                'inv_status' => 'SUSPEND',
            ]);
            Registrasi::where('reg_idpel', $d->inv_idpel)->update([
                'reg_status' => 'SUSPEND',
            ]);
        }
    }
    public function index(Request $request)
    {

        // $mutasi = DB::table('mutasi_sales')
        //     ->where('smt_user_id' , '25055121')
        //     ->orderBy('created_at') // To ensure correct chronological order
        //     ->select(
        //         'smt_user_id',
        //         'smt_kategori',
        //         'smt_deskripsi',
        //         'smt_kredit',
        //         // 'type',
        //         // 'amount',
        //         // 'description',
        //         'created_at',
        //         DB::raw('SUM(CASE WHEN smt_kategori = "debit" THEN smt_kredit ELSE 0 END) OVER (ORDER BY created_at) AS debit_balance'),
        //         DB::raw('SUM(CASE WHEN smt_kategori = "credit" THEN smt_kredit ELSE 0 END) OVER (ORDER BY created_at) AS credit_balance')
        //     )
        //     ->get();
            
        // // Add a final saldo column:
        // $mutasi->map(function ($transaction) {
        //     $transaction->saldo = $transaction->debit_balance - $transaction->credit_balance;
        //     // dd($transaction);
        //     return $transaction;
        // });

        // return $mutasi;

        // echo '<table><tr><td>'.$mutasi.'</td></tr></table>';
        // dd('test');




        // $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        // $data_pelanggan = Invoice::orderBy('inv_status', 'ASC','inv_tgl_isolir','ASC')
        // ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        //     ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
        //     ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
        //     ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
        //     // ->whereDate('inv_tgl_isolir', '<=', $data['now'])
        //     ->where('inv_status', '=', 'SUSPEND')
        //     // ->where('inv_status', '!=', 'ISOLIR')
        //     // ->where('inv_status', '!=', 'UNPAID')
        //     // ->where('inv_jenis_tagihan', '!=', 'FREE')
        //     ->get();

        //     foreach ($data_pelanggan as $value) {
        //        echo '<table><th><td></td>'.$value->inv_idpel.'</th></td>'.$value->input_nama.'</th></td>'.$value->inv_status.'</th></table>';
        //     }
        // dd('test');

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

        $query = Invoice::query()
        ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_status', '!=', 'PAID')
            // ->where('inv_status', '=', 'ISOLIR')
            // ->whereMonth('inv_tgl_isolir', '<=', '12')
            // ->whereYear('inv_tgl_isolir', '=', '2024')
            ->where('invoices.corporate_id',Session::get('corp_id'))
            ->orderBy('invoices.inv_id', 'ASC')
            ->orderBy('inv_tgl_jatuh_tempo', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('invoices.inv_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('invoices.inv_nolayanan', 'like', '%' . $data['q'] . '%');
                // $query->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('invoices.inv_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
                // $query->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
            });

        if ($data['bulan'])
            $query->whereMonth('invoices.inv_tgl_jatuh_tempo', date('m', strtotime($data['bulan'])))->whereYear('inv_tgl_jatuh_tempo', date('Y', strtotime($data['bulan'])));

        if ($data['data_inv'])
            $query->where('inv_status', '=', $data['data_inv']);
        // $export = $query->get();
        //         foreach ($export as $value) {
        //             echo '<table><tr><td>'.$value->inv_idpel.'</td><td>'.$value->inv_nolayanan.'</td><td>'.$value->input_nama.'</td><td>'.$value->paket_nama.'</td><td>'.$value->inv_tgl_jatuh_tempo.'</td><td>'.$value->inv_total.'</td><td>'.$value->inv_status.'</td></tr></table>';
        //         }
        //         dd('test');


        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->paginate(20);
        $data['inv_count_belum_terbayar'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '!=', 'PAID')->count() + Invoice::where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_bayar', '=', $month)->count();
        $data['inv_count_total'] = Invoice::where('corporate_id',Session::get('corp_id'))->whereMonth('inv_tgl_jatuh_tempo', '=', $month)->count();
        $data['inv_count_unpaid'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '=', 'UNPAID')->count();
        $data['inv_count_lunas'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_bayar', '=', $month)->count();
        $data['inv_belum_lunas'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '!=', 'PAID')->sum('inv_total');
        $data['inv_lunas'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '=', 'PAID')->whereMonth('inv_tgl_bayar', '=', $month)->sum('inv_total');
        $data['inv_count_suspend'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '=', 'SUSPEND')->count();
        $data['inv_count_isolir'] = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_status', '=', 'ISOLIR')->count();
        return view('Transaksi/list_invoice', $data);
    }
    public function paid(Request $request)
    {
        // dd('test');
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->addMonth(-0)->format('m');
        $data['q'] = $request->query('q');
        $data['bulan'] = $request->query('bulan');
        $data['tglbayar'] = $request->query('tglbayar');
        $invoice = Invoice::where('invoices.inv_status', '=', 'PAID')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            // ->where('invoices.inv_jenis_tagihan', '!=', 'FREE')
            ->whereMonth('invoices.inv_tgl_bayar', '=', $month)
            ->where('invoices.corporate_id',Session::get('corp_id'))
            ->orderBy('inv_tgl_bayar', 'DESC')
            ->where(function ($invoice) use ($data) {
                $invoice->orWhere('inv_id', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('input_nama', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('inv_tgl_bayar', 'like', '%' . $data['q'] . '%');
                $invoice->orWhere('reg_jenis_tagihan', 'like', '%' . $data['q'] . '%');
            });


        if ($data['bulan'])
            $invoice->whereMonth('inv_tgl_bayar', date('m', strtotime($data['bulan'])))->whereYear('inv_tgl_bayar', date('Y', strtotime($data['bulan'])));

        if ($data['tglbayar'])
            $invoice->whereDate('inv_tgl_bayar', $data['tglbayar']);

        // $export = $invoice->get();
        // foreach ($export as $value) {
        // echo '<table><tr><td>'.$value->inv_idpel.'</td><td>'.$value->inv_nolayanan.'</td><td>'.$value->input_nama.'</td><td>'.$value->paket_nama.'</td><td>'.$value->inv_tgl_jatuh_tempo.'</td><td>'.$value->inv_total.'</td><td>'.$value->inv_status.'</td><td>'.$value->reg_username.'</td></tr></table>';
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

        $invoice = Invoice::where('invoices.corporate_id',Session::get('corp_id'))->where('inv_id', '=', $inv_id)->first();
        $sub_invoice = SubInvoice::where('subinvoice_id', '=', $inv_id)->where('invoices.corporate_id',Session::get('corp_id'))->first();
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
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->where('inv_id', $id)
            ->where('invoices.corporate_id',Session::get('corp_id'))
             ->select([
                'invoices.*',
                'input_data.input_nama',
                'input_data.input_alamat_pasang',
                'input_data.input_hp',
                'input_data.input_email',
                'invoices.inv_nolayanan',
                // 'pakets.*',
                // 'sub_invoices.*',
                // 'sub_invoices.id as subinv_id',
            ])
            ->first();
            // dd($data['invoice']->inv_admin);

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
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'input_data.id')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->select([
                // 'invoices.*',
                // 'input_data.input_nama',
                // 'input_data.input_alamat_pasang',
                // 'input_data.input_hp',
                // 'input_data.input_email',
                // 'registrasis.*',
                // 'pakets.*',
                'sub_invoices.*',
                'sub_invoices.id as subinv_id',
            ])
            ->where('invoices.inv_id', $id)->get();


        $data['sumharga'] = SubInvoice::where('corporate_id',Session::get('corp_id'))->where('subinvoice_id', $id)->sum('subinvoice_harga');
        $data['sumppn'] = SubInvoice::where('corporate_id',Session::get('corp_id'))->where('subinvoice_id', $id)->sum('subinvoice_ppn');
        $data['ppnj'] = env('PPN');
        $data['akun_tunai'] = $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_type', 'TUNAI')->get();
        $data['akun'] = $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_type', 'TRANSFER')->get();
        $data['akun_all'] = $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->get();
        $data['ppn'] = SettingBiaya::where('corporate_id',Session::get('corp_id'))->first();


        return view('Transaksi/subinvoice', $data);
    }
    public function print_inv(Request $request, $id)
    {



        if ($request->cara_print == 1) {
            $admin_user = Auth::user()->id;
            $data['admin'] = Auth::user()->name;
            $data['invoice'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
                ->join('setting_akuns', 'setting_akuns.id', '=', 'invoices.inv_akun')
                ->where('invoices.inv_id', $id)->first();
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
            $data['akun'] = $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_kategori', '!=', 'LAPORAN')->get();
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
            $data['akun'] = $data['setting_akun'] = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_kategori', '!=', 'LAPORAN')->get();
            $data['ppn'] = SettingBiaya::first();


            return view('Transaksi/print_subinvoice', $data);
        }
    }
    public function addDiskon(Request $request, $id)
    {
        // return response()->json($id);
        $data['inv_diskon'] = $request->diskon;
        $data['inv_total'] = $request->total;
        Invoice::where('inv_id', $id)->where('invoices.corporate_id',Session::get('corp_id'))->update($data);
        return response()->json($data);
    }

    public function rollback(Request $request, $id)
    {
        // $data_laporan = Laporan::where('lap_inv',$id)->first();
        // if($data_laporan->)
        $swaktu = SettingWaktuTagihan::where('corporate_id',Session::get('corp_id'))->first();
        $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $admin_user = Auth::user()->id;
        $tampil = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
        ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
        // ->join('laporans', 'laporans.lap_inv', '=', 'invoices.inv_id')
        ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
        ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
            ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
            ->where('invoices.corporate_id',Session::get('corp_id'))
            ->where('invoices.inv_id', $id)
            ->select([
                // 'laporans.*',
                'registrasis.reg_idpel',
                'registrasis.reg_username',
                'registrasis.reg_password',
                'invoices.*',
                'sub_invoices.*',
                'input_data.input_nama',
                'pakets.paket_nama',
                'routers.*',
                'routers.id as id_router',
                ])
                ->first();
                // dd($tampil->id_router);
        if($tampil){
              $tgl_bayar = date('Y-m-d H:i:s', strtotime(Carbon::now()));

                    $now = Carbon::now();
                    $month = $now->format('m');
                    $year = $now->format('Y');


            $cek_hari_bayar = date('d', strtotime($tgl_bayar));
                if ($cek_hari_bayar >= 25) {
                    $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
                } else {
                    $if_tgl_bayar = $tgl_bayar;
                }

        $diskon = $tampil->inv_diskon ?? '0';

        // $sum_fee = FtthFee::where('corporate_id',Session::get('corp_id'))->where('fee_idpel',$tampil->reg_idpel)->sum('reg_fee');
        $sumppn = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn'); #hitung total ppn invoice
        $sumharga = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga'); #hitung total harga invoice
        // $sumhbph_uso = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_bph_uso'); #hitung total bph_uso invoice
        $total_debet = $sumharga + $sumppn - $diskon;
        #Kembalikan tanggal jatuh tempo ke tanggal sebelumnya
        $inv_tgl_isolir = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addDay($swaktu->wt_jeda_isolir_hari)->toDateString();
        $inv_tgl_tagih = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addDay(-$swaktu->wt_jeda_isolir_hari)->toDateString();
        $kurangi_tgl_jth_tempo = Carbon::create($tampil->inv_tgl_jatuh_tempo)->addMonth(-1)->toDateString();
        


        MutasiSales::where('corporate_id',Session::get('corp_id'))->where('mutasi_sales_inv_id',$id)->delete();

           $sumppn = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn'); #hitung total ppn invoice
            $sumharga = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga'); #hitung total harga invoice
            $sumhbph_uso = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_bph_uso'); #hitung total bph_uso invoice
            $diskon = $tampil->inv_diskon;
            $total_inv = $sumharga + $sumppn - $diskon;

        $data_lap['lap_fee_mitra'] = $tampil->lap_fee_mitra ?? '0';
         $data_lap['lap_id'] = time();
        $data_lap['corporate_id'] = $tampil->corporate_id;
        $data_lap['lap_inv'] =  '0';
        $data_lap['lap_tgl'] =  date('Y-m-d H:i:s', strtotime(Carbon::now()));
        $data_lap['lap_admin'] = $admin_user  ?? '0';
        $data_lap['lap_ppn'] = $tampil->lap_ppn ?? '0';
        $data_lap['lap_bph_uso'] = $tampil->lap_bph_uso ?? '0';
        $data_lap['lap_pokok'] = $tampil->lap_pokok  ?? '0';
        $data_lap['lap_jumlah'] = $tampil->lap_jumlah  ?? '0';
        $data_lap['lap_keterangan'] = 'Rollback INV- ' . $tampil->inv_id . ' ( ' . $tampil->input_nama . ' )';
        $data_lap['lap_akun'] = $tampil->inv_akun  ?? '-';
        $data_lap['lap_jenis_inv'] = "Debit";
        $data_lap['lap_status'] = 0;
        $data_lap['lap_img'] = "-";
        
        Laporan::create($data_lap);

        $update_lap['lap_inv'] = '0';
        Laporan::where('lap_inv', $id)->where('corporate_id',Session::get('corp_id'))->update($update_lap);

        $cek_role = DB::table('users')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', $tampil->inv_admin)
            ->first();

        if ($cek_role->role_id >= '10' && $cek_role->role_id <= '16') {

            $saldo = (new GlobalController)->total_mutasi($tampil->inv_admin);
            $total = $saldo + $total_debet; #SALDO MUTASI = DEBET - KREDIT
            $biller = MitraSetting::where('mts_user_id', $tampil->inv_admin)->first(); #mengambil biaya admni biller pada table mitra_setting
            $pengurangan_adm = -$biller->mts_komisi;

              $explode = explode('|', $request->metode_bayar_rolback);
                // $akun_rolback = $explode[0];
                $norek_rolback = $explode[1];
                $cek_cabar = SettingAkun::where('akun_type',$norek_rolback)->first();
            Mutasi::create([
                'corporate_id' => Session::get('corp_id'),
                'mt_admin' => $admin_user,
                'mt_mts_id' => $tampil->inv_admin,
                'mt_kategori' => 'ROLLBACK',
                'mt_cabar' => $cek_cabar ?? '-',
                'mt_deskripsi' => 'Rollback Invoice ' . $tampil->inv_id . ' ( ' . $tampil->input_nama . ' ) ',
                'mt_kredit' => $total_debet,
                'mt_saldo' => $total,
                'mt_biaya_adm' => $pengurangan_adm,
            ]);
        }

        if ($tgl_bayar >= $tampil->inv_tgl_jatuh_tempo) {
            $status = 'SUSPEND';
        } elseif ($tgl_bayar < $tampil->inv_tgl_jatuh_tempo) {
            $status = 'UNPAID';
            // dd($tampil->reg_router);
            $router = Router::whereId($tampil->id_router)->first();

            $ip =   $router->router_ip . ':' . $router->router_port_api;
            $user = $router->router_username;
            $pass = $router->router_password;
            $API = new RouterosAPI();
            $API->debug = false;

            if ($API->connect($ip, $user, $pass)) {
                $cek_secret = $API->comm('/ppp/secret/print', [
                    '?name' => $tampil->reg_username,
                ]);
                // dd($cek_secret);/
                if ($cek_secret) {
                    $API->comm('/ppp/secret/set', [
                        '.id' => $cek_secret[0]['.id'],
                        'profile' =>  $tampil->paket_nama == '' ? '' : $tampil->paket_nama,
                        'disabled' => 'no',
                    ]);
                } else {
                    $notifikasi = array(
                        'pesan' => 'Gagal melakukan Rollback. Data tidak ada mada Router',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.inv.sub_invoice', ['id' => $tampil->inv_id])->with($notifikasi);
                }
            }
        }

        $data['inv_akun'] = '0';
        // $data['inv_cabar'] = 'SYSTE,';
        $data['inv_tgl_bayar'] = '';
        $data['inv_status'] = $status;
        $data['inv_tgl_isolir'] = $inv_tgl_isolir;
        $data['inv_tgl_tagih'] = $inv_tgl_tagih;
        $data['inv_tgl_jatuh_tempo'] = $tampil->inv_tgl_jatuh_tempo;
        Invoice::where('inv_id', $id)->where('corporate_id',Session::get('corp_id'))->update($data);


        Registrasi::where('reg_idpel', $tampil->inv_idpel)->where('corporate_id',Session::get('corp_id'))->update([
            'reg_status' => $status,
            'reg_tgl_jatuh_tempo' => $kurangi_tgl_jth_tempo,
        ]);

        // dd($tampil->inv_idpel);
        $notifikasi = array(
            'pesan' => 'Berhasil melakukan Rollback',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $tampil->inv_id])->with($notifikasi);
    } else {
            $notifikasi = array(
                'pesan' => 'Rollback hanya bisa satu kali',
                'alert' => 'warning',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $tampil->inv_id])->with($notifikasi);

        }
    }

    public function payment(Request $request, $id)

    {


        $nama_user = Auth::user()->name; #NAMA USER
        $tgl_bayar = date('Y-m-d H:i:s', strtotime(Carbon::now()));

        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        $cek_inv = Laporan::where('lap_inv', $id)->where('corporate_id',Session::get('corp_id'))->where('lap_jenis_inv', 'Credit')->first();
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
               ->join('ftth_instalasis', 'ftth_instalasis.id', '=', 'registrasis.reg_idpel')
               ->join('data__odps', 'data__odps.id', '=', 'ftth_instalasis.data__odp_id')
               ->join('routers', 'routers.id', '=', 'ftth_instalasis.reg_router')
               ->where('invoices.corporate_id',Session::get('corp_id'))
               ->where('inv_id', $id)
               ->select([
                   'invoices.*',
                   'registrasis.*',
                   'input_data.input_nama',
                   'input_data.input_hp',
                   'pakets.paket_nama',
                   'data__odps.odp_id',
                   'routers.*',
                   'ftth_instalasis.*',
               ])
               ->first();
          

            //  dd($data_pelanggan);


            $date1 = Carbon::createFromDate($data_pelanggan->inv_tgl_jatuh_tempo); // start date
            $valid_date = Carbon::parse($date1)->toDateString();
            $valid_date = date('Y.m.d\\TH:i', strtotime($valid_date));
            $today = new \DateTime();
            $today->setTime(0, 0, 0);

            $match_date = \DateTime::createFromFormat("Y.m.d\\TH:i", $valid_date);
            $match_date->setTime(0, 0, 0);

            $diff = $today->diff($match_date);
            $diffDays = (int)$diff->format("%R%a");

            $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo)); #new
            $hari_tgl_tagih = date('d', strtotime($data_pelanggan->reg_tgl_tagih)); #new

            $inv0_tagih = Carbon::create($year . '-' . $month . '-' . $hari_tgl_tagih)->addMonth(1)->toDateString(); #new
            $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
            $inv0_jt_tempo = Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString(); #new



            # diffDays < -0 artinya jika pelanggan melakukan pembayaran sebelum jatuh tempo.
            #Jika pelanggan melakukan pembayaran sebelum jatuh tempo, maka tanggal jatuh tempo tidak berubah.
            # diffDays > -0 artinya jika pelanggan melakukan pembayaran setelah jatuh tempo.
            # Jika pelanggan melakukan pembayaran lewat dari jatuh tempo, maka tanggal jatuh tempo akan berubah ke tanggal pelanggan melakukan pembayaran.
            $cek_hari_bayar = date('d', strtotime($tgl_bayar));
            if ($diffDays < -0) {
                # Cek tanggal pembayaran.
                # Jika Pelanggan melakukan pembayaran di atas tanggal 24 maka, tanggal jatuh tempo akan berubah ketanggal 1 bulan berikutnya 
                if ($cek_hari_bayar >= 25) {
                    #Tambah 1 bulan dari tgl pembeyaran
                    #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
                    // dd('Bayar di atas tgl 25');
                    $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                    $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
                } else {
                    $inv1_tagih = Carbon::create($tgl_bayar)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                    $if_tgl_bayar = $tgl_bayar;
                    // dd('Bayar di bawah tgl 25');
                }
            } else {
                
                if ($cek_hari_bayar >= 25) {
                    #Tambah 1 bulan dari tgl pembeyaran
                    #Pembayaran di atas tanggal 24 maka akan di anggap bayar tgl 25 dan ditambah 1 bulan 
                    $addonemonth = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-25'))->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $inv1_tagih1 = Carbon::create($tgl_jt_tempo)->addDay(-1)->toDateString();
                    $inv1_jt_tempo = date('Y-m-d', strtotime(Carbon::create(date('Y-m-02', strtotime($addonemonth)))->addMonth(1)->toDateString()));
                    $if_tgl_bayar = date('Y-m-d', strtotime(Carbon::create(date($year . '-' . $month . '-01'))->addMonth(1)->toDateString()));
                    // dd('Bayar tepat waktu namun di atas tgl 25');
                } else {
                    $inv1_tagih = Carbon::create($data_pelanggan->inv_tgl_jatuh_tempo)->addMonth(1)->toDateString();
                    $inv1_tagih1 = Carbon::create($inv1_tagih)->addDay(-2)->toDateString();
                    $inv1_jt_tempo = Carbon::create($inv1_tagih)->toDateString();
                    $if_tgl_bayar = $tgl_bayar;
                    // dd('pembayaran tepat waktu dibawah tgl 25');
                }
            }
            
            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran
            
            
            if ($data_pelanggan->reg_inv_control == 0) {
                $reg['reg_tgl_jatuh_tempo'] = $inv0_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv0_tagih0;
            } else {
                $reg['reg_tgl_jatuh_tempo'] = $inv1_jt_tempo;
                $reg['reg_tgl_tagih'] = $inv1_tagih1;
            }
            // dd($inv1_jt_tempo);

            $admin_user = Auth::user()->id;

            $explode = explode('|', $request->transfer);
            $exp_tunai = explode('|', $request->tunai);
            
            if ($request->cabar == 'TUNAI') {
            //     $cek_akun = SettingAkun::where('corporate_id',Session::get('corp_id'));
            // if($cek_akun){
                
            // }
                $akun = $exp_tunai[0];
                $norek = $exp_tunai[1];
                $akun_nama = $exp_tunai[2];
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
                $filename = Session::get('corp_id') . '-' . str_replace(" ", "-", $data_pelanggan->inv_id) . '.jpeg';
                $path = 'bukti-transfer/' . $filename;
                Storage::disk('public')->put($path, file_get_contents($file));
            }
            

               $api_payment = (new ApiController)->Api_payment_ftth($data_pelanggan,$nama_user,$reg);
            if($api_payment == 0)
            {
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
            $datas['inv_tgl_bayar'] = $if_tgl_bayar;
            $datas['inv_bukti_bayar'] = $filename;
            $datas['inv_status'] = 'PAID';
            // dd($sum_fee);
            Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_id', $id)->update($datas);
            $sum_fee = FtthFee::where('corporate_id',Session::get('corp_id'))->where('fee_idpel',$data_pelanggan->reg_idpel)->sum('reg_fee');
            $sumppn = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn'); #hitung total ppn invoice
            $sumharga = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga'); #hitung total harga invoice
            $sumhbph_uso = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_bph_uso'); #hitung total bph_uso invoice
            $diskon = $data_pelanggan->inv_diskon;
            $total_inv = $sumharga + $sumppn - $diskon;
           
        
            
            #CEK JUMLAH INVOICE
            $cek_count_inv = Invoice::where('corporate_id',Session::get('corp_id'))
            ->where('inv_idpel', $data_pelanggan->inv_idpel)
            ->count();
            
            if($cek_count_inv >= 2){
                $mitra = FtthFee::join('mitra_settings','mitra_settings.mts_user_id','=','ftth_fees.reg_mitra')
                                    ->where('ftth_fees.fee_idpel',$data_pelanggan->inv_idpel)
                                    ->where('ftth_fees.corporate_id',Session::get('corp_id'))
                                    ->get();
                if($mitra){
                    $fee_mitra = $sum_fee  ?? '0';
                    foreach($mitra as $mit){
                        MutasiSales::create([
                            'mutasi_sales_mitra_id' => $mit->reg_mitra ?? '0',
                            'mutasi_sales_idpel' => $data_pelanggan->reg_idpel ?? '0',
                            'mutasi_sales_tgl_transaksi' => $if_tgl_bayar,
                            'mutasi_sales_admin' => $admin_user ?? '0',
                            'mutasi_sales_type' => 'Credit',
                            'mutasi_sales_deskripsi' => $data_pelanggan->input_nama ?? '0',
                            'mutasi_sales_jumlah' => $mit->reg_fee ?? '0',
                            'mutasi_sales_inv_id' => $id ?? '0',
                            'corporate_id' => Session::get('corp_id'),
                        ]);
                    }
                }
            } else {
                $fee_mitra = '0';
            }
            $lap['lap_id'] = time();
            $lap['corporate_id'] =Session::get('corp_id');
            $lap['lap_inv'] = $id;
            $lap['lap_tgl'] = $if_tgl_bayar;
            $lap['lap_fee_mitra'] = $fee_mitra ?? '0';
            $lap['lap_ppn'] = $sumppn ?? '0';
            $lap['lap_bph_uso'] = $data_pelanggan->reg_bph_uso ?? '0';
            $lap['lap_pokok'] = $total_inv - $sumhbph_uso - $sumppn - $fee_mitra ?? '0';
            $lap['lap_jumlah'] = $total_inv ?? '0';
            $lap['lap_admin'] = $admin_user;
            $lap['lap_akun'] = $akun;
            $lap['lap_keterangan'] = 'INV-'.$id.' | '.$data_pelanggan->input_nama;
            $lap['lap_jenis_inv'] = 'Credit';
            $lap['lap_status'] = 0;
            Laporan::create($lap);
           
            $reg['reg_status'] = 'PAID';

            Registrasi::where('corporate_id',Session::get('corp_id'))->where('reg_idpel', $data_pelanggan->reg_idpel)->update($reg);

            $status = (new GlobalController)->whatsapp_status();
            if($status){
                if ($status->wa_status == 'Enable') {
                    $pesan_group['status'] = '0';
                } else {
                    $pesan_group['status'] = '10';
                }
            } else{
                $pesan_group['status'] = '10';
            }

            $pesan_group['pesan_id_site'] = '1';
            $pesan_group['corporate_id'] = Session::get('corp_id');
            $pesan_group['layanan'] = 'CS';
            $pesan_group['ket'] = 'payment';
            $pesan_group['target'] = $data_pelanggan->input_hp;
            $pesan_group['nama'] = $data_pelanggan->input_nama;
            $pesan_group['pesan'] = '
Terima kasih 🙏
Pembayaran invoice sudah kami terima
*************************
No.Layanan : ' . $data_pelanggan->reg_nolayanan . '
Pelanggan : ' . $data_pelanggan->input_nama . '
Invoice : *' . $data_pelanggan->inv_id . '*
Paket : ' . $data_pelanggan->paket_nama . '
Total : *Rp' . number_format($data_pelanggan->inv_total) . '*

Tanggal lunas : ' . date('d-m-Y H:i:s', strtotime(Carbon::now())) . '
Layanan sudah aktif dan dapat digunakan sampai dengan *' . $reg['reg_tgl_jatuh_tempo'] . '*

BY : ' . $nama_user . '
*************************
--------------------
Pesan ini bersifat informasi dan tidak perlu dibalas
*'.Session::get('app_brand').'*';
            Pesan::create($pesan_group);
                $notifikasi = array(
                                'pesan' => 'Berhasil melakukan pembayaran',
                                'alert' => 'success',
                            );
                            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
            }  else if($api_payment == 1){
                // $notifikasi = array(
                //                 'pesan' => 'Berhasil melakukan pembayaran. Pelanggan sedang tidak aktif',
                //                 'alert' => 'success',
                //             );
                //             return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
            } else if($api_payment == 3){
                $notifikasi = array(
                        'pesan' => 'Pembayaran tidak berhasil. Router Disconnected',
                        'alert' => 'error',
                    );
                    return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
            }


            // dd($api_payment);
        }
    }



    public function addons(Request $request, $id)
    {
        $unp = Invoice::where('inv_id', $id)->first();
        if ($unp) {
            $data['corporate_id'] = Session::get('corp_id');
            $data['subinvoice_id'] = $id;
            $data['subinvoice_deskripsi'] = $request->Deskripsi;
            $data['subinvoice_qty'] = $request->qty;
            $data['subinvoice_harga'] = $request->total;
            $data['subinvoice_ppn'] = $request->ppn;
            $data['subinvoice_total'] = $request->total;
            $data['subinvoice_status'] = '1';
            $upd['inv_total'] = $unp->inv_total + $request->total;
            SubInvoice::create($data);
            Invoice::where('invoices.corporate_id',Session::get('corp_id'))->where('inv_id', $id)->update($upd);
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
        // dd($id);
        $unp = Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_id', $inv)->first();
        $upd['inv_total'] = $unp->inv_total - $tot;
        Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_id', $inv)->update($upd);
        SubInvoice::where('corporate_id',Session::get('corp_id'))->where('id', $id)->delete();
        $notifikasi = array(
            'pesan' => 'Berhasil menghapus addons',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.sub_invoice', ['id' => $inv])->with($notifikasi);
    }
    // public function update_inv(Request $request, $inv_id)
    // {
    //     $data_pelanggan = Invoice::where('inv_id', '=', $inv_id)
    //         ->first();
    //     $swaktu = SettingWaktuTagihan::first();
    //     $tgl_tagih =  Carbon::create($request->tgl_jth_tempo)->addDay(-$swaktu->wt_jeda_tagihan_pertama)->toDateString();
    //     $periode = date('d-m-Y', strtotime(Carbon::create($request->tgl_jth_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($request->tgl_jth_tempo)->addMonth(1)->toDateString()));

    //     $cek_hari = date('d', strtotime($request->tgl_jth_tempo));
    //     if ($cek_hari == 31) {
    //         $jeda_waktu = '0';
    //     } elseif ($cek_hari == 30) {
    //         $jeda_waktu = '0';
    //     } else {
    //         $jeda_waktu = $swaktu->wt_jeda_isolir_hari;
    //     }
    //     $tgl_isolir =  Carbon::create($request->tgl_jth_tempo)->addDay($jeda_waktu)->toDateString();
    //     // dd($data_pelanggan->inv_profile);

    //     Invoice::where('inv_id', '=', $inv_id)->update([
    //         'inv_tgl_tagih' => $tgl_tagih,
    //         'inv_tgl_jatuh_tempo' => date('Y-m-d', strtotime($request->tgl_jth_tempo)),
    //         'inv_tgl_isolir' => $tgl_isolir,
    //         'inv_periode' => $periode,
    //     ]);

    //     SubInvoice::where('subinvoice_id', '=', $inv_id)->update(
    //         [
    //             'subinvoice_id' => $inv_id,
    //             'subinvoice_deskripsi' => $data_pelanggan->inv_profile . ' ( ' . $periode . ' )',
    //         ]
    //     );
    //     $notifikasi = array(
    //         'pesan' => 'Berhasil merubah tanggal jatuh tempo',
    //         'alert' => 'success',
    //     );
    //     return redirect()->route('admin.inv.sub_invoice', ['id' => $inv_id])->with($notifikasi);
    // }
    // public function update_tgl_bayar(Request $request, $inv_id)
    // {
    //     $data_pelanggan = Invoice::where('inv_id', '=', $inv_id)
    //         ->first();

    //     Invoice::where('inv_id', '=', $inv_id)->update([
    //         'inv_tgl_bayar' => date('Y-m-d', strtotime($request->tgl_bayar)),
    //     ]);

    //     $notifikasi = array(
    //         'pesan' => 'Berhasil merubah tanggal bayar',
    //         'alert' => 'success',
    //     );
    //     return redirect()->route('admin.inv.sub_invoice', ['id' => $inv_id])->with($notifikasi);
    // }

    // public function add_inv_manual(Request $request)
    // {
    //     dd($request->add_nolayanan);
    //     $now = Carbon::now();
    //     $month = $now->format('m');
    //     $hitung = Invoice::whereMonth('tgl_jatuh_tempo', $month)->counnt();
    //     $year = $now->format('Y');
    //     $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'reg_idpel')
    //         ->join('pakets', 'pakets.paket_id', '=', 'reg_profile')
    //         ->where('reg_nolayanan', '=', 240175402)
    //         ->first();
    //     $swaktu = SettingWaktuTagihan::first();
    //     $i = 1338;
    //     $inv_id = rand(1000, 1999) . $i++;
    //     $hari_jt_tempo = date('d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo));
    //     $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($data_pelanggan->reg_tgl_tagih));
    //     $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
    //     $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($data_pelanggan->reg_tgl_jatuh_tempo));
    //     $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($swaktu->wt_jeda_tagihan_pertama)->toDateString();
    //     // dd($tgl_isolir);
    //     Invoice::create([
    //         'inv_id' => $inv_id,
    //         'inv_status' => 'UNPAID',
    //         'inv_idpel' => $data_pelanggan->reg_idpel,
    //         'inv_nolayanan' => $data_pelanggan->reg_nolayanan,
    //         'input_nama' => $data_pelanggan->input_nama,
    //         'inv_jenis_tagihan' => $data_pelanggan->reg_jenis_tagihan,
    //         'inv_profile' => $data_pelanggan->paket_nama,
    //         'inv_mitra' => 'SYSTEM',
    //         'inv_kategori' => 'OTOMATIS',
    //         'inv_tgl_tagih' => $hari_tgl_tagih,
    //         'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
    //         'inv_tgl_isolir' => $tgl_isolir,
    //         'inv_periode' => $periode1blan,
    //         'inv_total' => $data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
    //     ]);

    //     SubInvoice::create(
    //         [
    //             'subinvoice_id' => $inv_id,
    //             'subinvoice_deskripsi' => $data_pelanggan->paket_nama . ' ( ' . $periode1blan . ' )',
    //             'subinvoice_harga' => $data_pelanggan->reg_harga + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
    //             'subinvoice_ppn' => $data_pelanggan->reg_ppn,
    //             'subinvoice_total' => $data_pelanggan->reg_harga + $data_pelanggan->reg_ppn + $data_pelanggan->reg_kode_unik + $data_pelanggan->reg_dana_kas + $data_pelanggan->reg_dana_kerjasama,
    //             'subinvoice_qty' => 1,
    //             'subinvoice_status' => 0,
    //         ]
    //     );
    // }



    public function test1(Request $request)
    {
        $data['q'] = $request->query('q');


        $query = Registrasi::select('input_data.*', 'registrasis.*', 'registrasis.created_at as tgl', 'pakets.*', 'routers.*')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->join('routers', 'routers.id', '=', 'registrasis.reg_router')
            ->whereDay('reg_tgl_jatuh_tempo', '>=', '24')
            ->whereMonth('reg_tgl_jatuh_tempo', '=', '10')
            ->orderBy('tgl', 'ASC')
            ->where(function ($query) use ($data) {
                $query->where('reg_mac', 'like', '%' . $data['q'] . '%');
            });

        $rubah_tgl = $query->get();
        $count = $query->count();

        foreach ($rubah_tgl as $key) {
            // Registrasi::where('reg_idpel', $key->reg_idpel)->update(
            //     [
            //         'reg_tgl_jatuh_tempo' => '',
            //     ]
            // );

            echo '<table><tr><td>' . $key->reg_idpel . '</td><td>' . $key->input_nama . '</td><td>' . $key->reg_tgl_jatuh_tempo . '</td></tr></table>';
        }
        dd($count);
    }
    public function test2()
    {
        //   $smt_user_id = 2203910401;
        //               $inv_idpel = 2203910401;
        //                 $data_pelanggan =  Invoice::where('inv_nolayanan','=', 22010960601)
        //                 ->whereMonth('inv_tgl_bayar', '=', 05)->first();



        //                 $mutasi_sales['smt_user_id'] = $smt_user_id;
        //                 $mutasi_sales['smt_admin'] = 10;
        //                 $mutasi_sales['smt_idpel'] = $data_pelanggan->inv_idpel;
        //                 $mutasi_sales['smt_tgl_transaksi'] = date('Y-m-d H:i:s', strtotime($data_pelanggan->updated_at));
        //                 $mutasi_sales['smt_kategori'] = 'PENDAPATAN';
        //                 $mutasi_sales['smt_deskripsi'] = $data_pelanggan->input_nama;
        //                 $mutasi_sales['smt_cabar'] = $data_pelanggan->inv_akun;
        //                 $mutasi_sales['smt_kredit'] = '15000';
        //                 $mutasi_sales['smt_debet'] = 0;
        //                 $mutasi_sales['smt_saldo'] = 0;
        //                 $mutasi_sales['smt_biaya_adm'] = 0;
        //                 $mutasi_sales['smt_status'] = 0;
        //                 MutasiSales::create($mutasi_sales);
        //                 dd($mutasi_sales);

        // $inputdata = InputData::where('input_status','MIGRASI')->count();
        // $inputdata = InputData::get();
        
        // foreach($inputdata as $d){
            //     // InputData::whereIn('id',[$d->id])->update([
                //     //     'id' => $d->input_id_baru,
                //     // ]);
                //     echo '<table><tr><td>'.$d->id.'</td></tr></table>';
    //             // }
    //             $registrasi = Registrasi::get();
    //             // $registrasi = InputData::where('input_status','MIGRASI')->get();
    //             // dd($inputdata);
    //             $no = 1;
    //             foreach($registrasi as $d){
    //                 $cek = InputData::whereIn('id',[$d->id])->get();
    //                 // InputData::whereIn('id',[$d->id])->update([
    //                     //     'input_status' => 'REGIST',
    //                     // ]);

    //                     foreach ($cek as $value) {
    //                         # code...
    //                         echo '<table><tr><td>'.$no++.'</td><td>'.$d->reg_idpel.'</td><td>'.$value->input_nama.'</td></tr></table>';
    //                     }
    //                 }
    // dd('test');

    // $cek = InputData::get();
    // foreach($cek as $c){
    //     echo $c->id. '<br>';

    // }

    // $barang = Data_Barang::whereIn('barang_id',[88185,83893,15053,56281,76082,37206,27606,49963,23541,87741,83376])->update([
    //     'barang_digunakan' => 0,
    // ]);

    // $cek_null = Invoice::where('inv_total','NULL')->get();
    // dd($cek_null);
    // $mitra = 202504361219;
    // $nolayanan = 24031350221;
    // $invoice = Invoice::join('input_data','input_data.id','=','invoices.inv_idpel')
    //                     ->join('registrasis','registrasis.reg_idpel','=','invoices.inv_idpel')
    //                     // ->join('ftth_fees','ftth_fees.fee_idpel','=','invoices.inv_idpel')
    //                     // ->where('input_data.input_sales', $mitra)
    //                     // ->whereMonth('registrasis.reg_tgl_pasang','<=', 5)
    //                     // ->whereYear('registrasis.reg_tgl_pasang', 2025)
    //                     ->where('invoices.inv_nolayanan', $nolayanan)
    //                     ->whereMonth('invoices.inv_tgl_tagih', 6)
    //                     ->whereYear('invoices.inv_tgl_tagih', 2025)
    //                     ->get();
    // foreach ($invoice as $d) {
    //     // MutasiSales::create([
    //     //     'corporate_id'=> 240110001,
    //     //     'mutasi_sales_mitra_id'=> $mitra,
    //     //     'mutasi_sales_tgl_transaksi'=> $d->inv_tgl_bayar,
    //     //     'mutasi_sales_idpel'=> $d->inv_idpel,
    //     //     'mutasi_sales_admin'=> $d->inv_admin,
    //     //     'mutasi_sales_type'=> 'Credit',
    //     //     'mutasi_sales_jumlah'=> ,
    //     //     'mutasi_sales_deskripsi'=> $d->input_nama,
    //     //     'mutasi_sales_inv_id'=> $d->inv_id,
    //     // ])
    //     // $tgl_jatuh_tempo = Carbon::create($d->inv_tgl_bayar)->addMonth(1)->toDateString();
    //     // $tagih = Carbon::create($d->inv_tgl_bayar)->addMonth(1)->toDateString();
    //     // $tgl_tagih = Carbon::create($tagih)->addDay(-2)->toDateString();
    //     Registrasi::where('reg_nolayanan',$nolayanan)->update([
    //         // 'reg_tgl_jatuh_tempo' => $tgl_jatuh_tempo,
    //         // 'reg_tgl_tagih' => $tgl_tagih,
    //         'reg_status' => $d->inv_status,
    //     ]);
    //     // dd($tgl_jatuh_tempo);
    //     echo '<table><tr><th>NAMA</th><th>JATUH TEMPO</th><th>TGL TAGIH</th><th>STATUS</th><tr><tbody><td>'.$d->input_nama.'</td><td>'.$d->reg_tgl_jatuh_tempo.'</td><td>'.$d->reg_tgl_tagih.'</td><td>'.$d->reg_status.'</td></tr></tbody></table>';
    //     // echo '<table><tr><td>'.$d->inv_id.'</td><td>'.$d->inv_tgl_bayar.'</td><td>'.$d->input_nama.'</td><td>'.$d->reg_tgl_pasang.'</td><td>'.$d->input_sales.'</td><td>'.$d->reg_fee.'</td></tr></table>';
    // }

    // $invoice1 = Invoice::join('input_data','input_data.id','=','invoices.inv_idpel')
    //                     ->join('registrasis','registrasis.reg_idpel','=','invoices.inv_idpel')
    //                     // ->join('ftth_fees','ftth_fees.fee_idpel','=','invoices.inv_idpel')
    //                     // ->where('input_data.input_sales', $mitra)
    //                     // ->whereMonth('registrasis.reg_tgl_pasang','<=', 5)
    //                     // ->whereYear('registrasis.reg_tgl_pasang', 2025)
    //                     ->where('invoices.inv_nolayanan', $nolayanan)
    //                     ->whereMonth('invoices.inv_tgl_tagih', 6)
    //                     ->whereYear('invoices.inv_tgl_tagih', 2025)
    //                     ->get();
    // foreach ($invoice1 as $d1) {

    //     echo '<table><tr><th>NAMA</th><th>JATUH TEMPO</th><th>TGL TAGIH</th><th>STATUS</th><tr><tbody><td>'.$d1->input_nama.'</td><td>'.$d1->reg_tgl_jatuh_tempo.'</td><td>'.$d1->reg_tgl_tagih.'</td><td>'.$d1->reg_status.'</td></tr></tbody></table>';
    // }

    // $variable = Data_BarangKeluar::where('bk_idpel','!=','0')
    // ->join('input_data','input_data.input_id_lama', '=', 'data__barang_keluars.bk_idpel')
    // ->select('data__barang_keluars.bk_idpel','data__barang_keluars.bk_kategori','data__barang_keluars.bk_id_barang','input_data.id as idpel','input_data.input_id_lama')
    // ->get();
    //             // ->where('ftth_instalasis.reg_router',5)->get();

    // foreach ($variable as $v) {
    //     echo '<table><tr><td>'.$v->bk_id_barang.'</td><td>'.$v->bk_kategori.'</td><td>'.$v->bk_idpel.'</td><td>'.$v->idpel.'</td></tr></table>';
    //    $value= Data_BarangKeluar::where('bk_idpel',$v->bk_idpel)->update([
    //      'bk_idpel' => $v->idpel,
    //     ]);
    // }


    }
}
