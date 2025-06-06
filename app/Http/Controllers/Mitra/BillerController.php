<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\PSB\ApiController;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Barang\Barang;
use App\Models\Barang\SubBarang;
use App\Models\Barang\supplier;
use App\Models\Global\ConvertNoHp;
use App\Models\Mitra\MitraSetting;
use App\Models\Mitra\Mutasi;
use App\Models\Mitra\MutasiSales;
use App\Models\Pesan\Pesan;
use App\Models\PSB\FtthFee;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Router\Paket;
use App\Models\Router\Router;
use App\Models\Router\RouterosAPI;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\SubInvoice;
use App\Models\Transaksi\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class BillerController extends Controller
{
    public function maintenance()
    {
        // dd('test');
        return view('auth/maintenance');
    }

    public function getpelanggan(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $query =  DB::table('invoices')
        // ->join('invoices', 'invoices.inv_idpel', '=', 'registrasis.reg_idpel')
        ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
        ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
        ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
        ->where('invoices.inv_status', '!=', 'PAID')
        ->where('invoices.inv_id', '=', $id)
        ->orWhere('registrasis.reg_nolayanan', '=', $id)
        ->orWhere('input_data.input_hp', '=', $id)
        ->orWhere('input_data.input_nama', '=', $id)
        ->where('invoices.corporate_id',Session::get('corp_id'))
        ->latest('invoices.inv_tgl_jatuh_tempo')
        ->select([
                'registrasis.reg_nolayanan',
                'registrasis.reg_idpel',
                'registrasis.reg_jenis_tagihan',
                'input_data.input_alamat_pasang',
                'input_data.input_nama',
                'input_data.input_hp',
                'invoices.*',
                'sub_invoices.*',
                ])
            ->first();
        if ($query->inv_status == 'PAID') {
            $data['data'] = $query->inv_status;
        } else {
            $data['data'] = $query;
            $data['biller'] = MitraSetting::where('mts_user_id', $admin_user)->where('corporate_id',Session::get('corp_id'))->first();
            $data['saldo'] = (new GlobalController)->total_mutasi($admin_user);
            $data['sumharga'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga');
            $data['sumppn'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn');
        }
        return response()->json($data);
    }

    public function print(Request $request, $id)
    {
        $admin_user = Auth::user()->id;
        $data['admin'] = Auth::user()->name;
        $data['data'] = DB::table('invoices')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->join('setting_akuns', 'setting_akuns.akun_id', '=', 'invoices.inv_akun')
            ->join('users', 'users.id', '=', 'invoices.inv_admin')
            ->where('invoices.inv_id', '=', $id)
            ->orWhere('invoices.inv_nolayanan', '=', $id)
            ->where('invoices.inv_status', '=', 'PAID')
            ->first();
        $data['sumharga'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_harga');
        $data['sumppn'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->sum('subinvoice_ppn');
        $data['datainvoice'] = SubInvoice::where('subinvoice_id', $data['data']->inv_id)->get();
        $data['biller'] = MitraSetting::where('mts_user_id', $admin_user)->first();
        $data['saldo'] = (new globalController)->total_mutasi($admin_user);


        return view('biller/print', $data);
    }

    public function mutasi()
    {
        $admin_user = Auth::user()->id;
        $data['tittle'] = 'MITRA';
        $query =  DB::table('mutasis')
            ->select('mutasis.*', 'mutasis.created_at as tgl_trx', 'mitra_settings.*')
            ->orderBy('mutasis.id', 'DESC')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
            ->where('mutasis.mt_mts_id', '=', $admin_user);
        $data['mutasi'] = $query->get();

        return view('biller/mutasi', $data);
    }
    
   
    public function mutasi_pdf(Request $request)
    {
        $data['admin_user'] = Auth::user()->id;
        $data['admin_name'] = Auth::user()->name;

        $data['start_date'] =  $request->start_date;
        $data['end_date'] =  $request->end_date;

        $query =  DB::table('mutasis')->select('mutasis.*', 'mutasis.created_at as tgl', 'mitra_settings.*')
            ->orderBy('tgl', 'ASC')
            ->join('mitra_settings', 'mitra_settings.mts_user_id', '=', 'mutasis.mt_mts_id')
            ->where('mutasis.mt_mts_id', '=', $data['admin_user'])
            ->whereDate('mutasis.created_at', '>=', $data['start_date'])
            ->whereDate('mutasis.created_at', '<=', $data['end_date']);
        $data['mutasi'] = $query->get();
        $data['saldo'] = (new globalController)->total_mutasi($data['admin_user']);

        $pdf = App::make('dompdf.wrapper');
        $html = view('biller/pdf', $data)->render();
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Mutasi.pdf');
    }
    public function payment()
    {
        $tagihan_kedepan = Carbon::now()->addday(8)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();

        $data['tittle'] = 'Payment';
        $data['input_data'] = Invoice::select('input_data.*', 'input_data.id as idp', 'invoices.*', 'registrasis.reg_nolayanan')
            ->join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            // ->whereDate('inv_tgl_jatuh_tempo', '>', $tagihan_kebelakang)
            ->whereDate('inv_tgl_jatuh_tempo', '<=', $tagihan_kedepan)
            ->where('inv_status', '!=', 'PAID')
            ->where('invoices.corporate_id',Session::get('corp_id'))->get();

        return view('biller/payment', $data);
    }
    public function paymentbytagihan($inv_id)
    {
        $data['invoice_id'] = $inv_id;
        return view('biller/paymentbytagihan', $data);
    }

    public function index(Request $request)
    {
        $month = Carbon::now()->format('m');
        $bulan_lalu = date('m', strtotime(Carbon::create(Carbon::now())->addMonth(-1)->toDateString()));
        $tagihan_kedepan = Carbon::now()->addday(5)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();

        $admin_user = Auth::user()->id;
        $data['nama'] = Auth::user()->name;

        $role = (new globalController)->data_user($admin_user);
        $data['role'] = $role->name;

        $data['saldo'] = (new globalController)->total_mutasi($admin_user);
        $data['biaya_adm'] = DB::table('mutasis')->whereRaw('extract(month from created_at) = ?', [$month])->where('mt_mts_id', $admin_user)->where('corporate_id',Session::get('corp_id'))->sum('mt_biaya_adm');

        $data['q'] = $request->query('q');
        $query_isolir = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->where('registrasis.reg_progres', '=', '5')
            ->where('registrasis.reg_status', '!=', 'PAID')
            ->whereDate('reg_tgl_jatuh_tempo', '<', $tagihan_kebelakang)
            ->where('registrasis.corporate_id',Session::get('corp_id'))
            ->orderBy('registrasis.reg_tgl_jatuh_tempo', 'ASC')
            ->where(function ($query_isolir) use ($data) {
                $query_isolir->Where('registrasis.reg_nolayanan', 'like', '%' . $data['q'] . '%');
                $query_isolir->orWhere('input_data.input_nama', 'like', '%' . $data['q'] . '%');
                $query_isolir->orWhere('registrasis.reg_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            });
        $data['pengambilan_perangkat'] =  $query_isolir->get();

        $data['count_pengambilan_perangkat'] = $query_isolir->count();

        $data['data'] = Invoice::where('inv_status', '=', 'PAID')->where('inv_admin', $admin_user)->get();
        return view('biller/index', $data);
    }
   
    
    
    
    public function bayar(Request $request, $id)
    {
        
        // $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
        $tgl_bayar = date('Y-m-d H:i:s', strtotime(Carbon::now()));
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        
        $admin_user = Auth::user()->id; #ID USER
        $nama_user = Auth::user()->name; #NAMA USER
        $mitra = MitraSetting::where('corporate_id',Session::get('corp_id'))->where('mts_user_id', $admin_user)->where('mts_limit_minus', '!=', '0')->first();



        $saldo_mutasi = (new GlobalController)->total_mutasi($admin_user); #Cek saldo mutasi terlebih dahulu sebelum melakukan pemabayaran
        $cek_tagihan = (new GlobalController)->data_tagihan($id); #cek data tagihan pembayaran
        $sumharga = SubInvoice::where('corporate_id',Session::get('corp_id'))->where('subinvoice_id', $cek_tagihan->inv_id)->sum('subinvoice_harga'); #hitung total harga invoice
        $sumppn = SubInvoice::where('corporate_id',Session::get('corp_id'))->where('subinvoice_id', $cek_tagihan->inv_id)->sum('subinvoice_ppn'); #hitung total ppn invoice
        $total_bayar = $sumharga + $sumppn - $cek_tagihan->inv_diskon;
        
        
        if ($mitra) {
            $saldo_mitra =  '-' . $mitra->mts_limit_minus <= ($saldo_mutasi - $total_bayar);
        } else {
            $saldo_mitra =  $saldo_mutasi > $total_bayar;
        }
        if ($saldo_mitra) {
            $biller = MitraSetting::where('mts_user_id', $admin_user)->first(); #mengambil biaya admni biller pada table mitra_setting

            
           
                 $data_pelanggan = (new GlobalController)->data_tagihan($id);
             
            
            // dd($data_pelanggan);
            
            
            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran
            
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
            $saldo = (new GlobalController)->total_mutasi($admin_user);
            // $saldo = (new GlobalController)->total_mutasi($admin_user);
            $pembayaran = $sumharga + $sumppn - $data_pelanggan->inv_diskon;
            $total = $saldo - $pembayaran; #SALDO MUTASI = DEBET - KREDIT
            // return response()->json($total);
            $admin_nama = Auth::user()->name;
            $admin_hp = Auth::user()->hp;
            $api_payment = (new ApiController)->Api_payment_ftth($data_pelanggan,$reg);
            $akun = SettingAkun::where('akun_type','TUNAI')->where('corporate_id',Session::get('corp_id'))->select('akun_nama','id')->first();
            //  return response()->json($api_payment);
        if($api_payment == 0)
        {

            $sum_fee = FtthFee::where('corporate_id',Session::get('corp_id'))->where('fee_idpel',$data_pelanggan->reg_idpel)->sum('reg_fee');
            $sumppn = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_ppn'); #hitung total ppn invoice
            $sumharga = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_harga'); #hitung total harga invoice
            $sumhbph_uso = SubInvoice::where('subinvoice_id', $id)->where('corporate_id',Session::get('corp_id'))->sum('subinvoice_bph_uso'); #hitung total bph_uso invoice
            $diskon = $data_pelanggan->inv_diskon;
            $total_inv = $sumharga + $sumppn - $diskon;




            $datas['inv_cabar'] = 'BILLER';
            $datas['inv_admin'] = $admin_user ?? '';
            $datas['inv_akun'] = $akun->id  ?? '';
            $datas['inv_reference'] = '-';
            $datas['inv_payment_method'] = $admin_nama  ?? '';
            $datas['inv_payment_method_code'] = $admin_hp  ?? '0';
            $datas['inv_total_amount'] = $total_inv  ?? '0';
            $datas['inv_fee_merchant'] = '0';
            $datas['inv_fee_customer'] = '0';
            $datas['inv_total_fee'] = '0';
            $datas['inv_amount_received'] = '0';
            $datas['inv_tgl_bayar'] = $if_tgl_bayar ?? '';
            $datas['inv_bukti_bayar'] = '-';
            $datas['inv_status'] = 'PAID';
            Invoice::where('corporate_id',Session::get('corp_id'))->where('inv_id', $id)->update($datas);



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
                                    'mutasi_sales_tgl_transaksi' => $if_tgl_bayar ?? '',
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
                        $lap['lap_tgl'] = $if_tgl_bayar ?? '';
                        
                        $lap['lap_fee_mitra'] = $fee_mitra ?? '0';
                        $lap['lap_ppn'] = $sumppn ?? '0';
                        $lap['lap_bph_uso'] = $data_pelanggan->reg_bph_uso ?? '0';
                        $lap['lap_pokok'] = $total_inv - $sumhbph_uso - $sumppn - $fee_mitra ?? '0';
                        $lap['lap_jumlah'] = $total_inv ?? '0';
                        $lap['lap_admin'] = $admin_user;
                        $lap['lap_akun'] = $akun->id;
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

                        $mutasi['mt_admin'] = $admin_user;
                        $mutasi['corporate_id'] = Session::get('corp_id');
                        $mutasi['mt_mts_id'] = $admin_user;
                        $mutasi['mt_kategori'] = 'PEMBAYARAN';
                        $mutasi['mt_deskripsi'] = $data_pelanggan->input_nama . ' INVOICE-' . $data_pelanggan->inv_id;
                        $mutasi['mt_debet'] = $total_inv;
                        $mutasi['mt_kredit'] = '0';
                        $mutasi['mt_saldo'] = $total;
                        $mutasi['mt_biaya_adm'] = $biller->mts_komisi;
                        $mutasi['mt_cabar'] = 'BILLER';
                        
                        Mutasi::create($mutasi);
            
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
Total : *Rp' . number_format($total_inv) . '*

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
                        return response()->json($notifikasi);
                    } else if($api_payment == 1){
                        #Berhasil melakukan pembayaran. Pelanggan sedang tidak aktif
                    } else if($api_payment == 3){
                        $notifikasi = array(
                            'pesan' => 'Pembayaran tidak berhasil. Router Disconnected',
                            'alert' => 'error',
                        );
                        return response()->json($notifikasi);
                    }
        } else {
            $notifikasi = array(
                'pesan' => 'Transaksi Gagal. Saldo anda tidak cukup',
                'alert' => 'error',
            );
            return response()->json($notifikasi);
        }
    }

    public function list_tagihan(Request $request)
    {
        $tagihan_kedepan = Carbon::now()->addday(5)->format('Y-m-d');
        $tagihan_kebelakang = Carbon::create($tagihan_kedepan)->addMonth(-1)->toDateString();


        // dd($tagihan_kebelakang);

        $pasang_bulan_ini = Carbon::now()->addMonth(-0)->format('Y-m-d');
        $pasang_bulan_lalu = Carbon::now()->addMonth(-1)->format('Y-m-d');
        $pasang_3_bulan_lalu = Carbon::now()->addMonth(-2)->format('Y-m-d');
        // $bulan_ini = date('m', strtotime(Carbon::now()));


        $data['data_bulan'] = $request->query('data_bulan');
        $data['data_inv'] = $request->query('data_inv');
        $data['q'] = $request->query('q');

        $query = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
            ->join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
            ->where('inv_status', '!=', 'PAID')
            ->whereDate('inv_tgl_jatuh_tempo', '>', $tagihan_kebelakang)
            ->whereDate('inv_tgl_jatuh_tempo', '<=', $tagihan_kedepan)
            ->orderBy('inv_tgl_jatuh_tempo', 'ASC')
            ->where(function ($query) use ($data) {
                $query->where('inv_id', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nolayanan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('inv_tgl_jatuh_tempo', 'like', '%' . $data['q'] . '%');
            });

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


        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->get();
        $data['inv_count_unpaid'] = Invoice::where('inv_status', '=', 'UNPAID')->count();
        $data['inv_belum_lunas'] = Invoice::where('inv_status', '!=', 'PAID')->sum('inv_total');
        $data['inv_lunas'] = Invoice::where('inv_status', '=', 'PAID')->sum('inv_total');
        $data['inv_count_suspend'] = Invoice::where('inv_status', '=', 'SUSPEND')->count();
        $data['inv_count_isolir'] = Invoice::where('inv_status', '=', 'ISOLIR')->count();
        $data['inv_count_lunas'] = Invoice::where('inv_status', '=', 'PAID')->count();
        return view('biller/list_tagihan', $data);
    }

    public function biller_putus_berlanggan(Request $request, $idpel) {}
}
