<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\Laporan;
use App\Models\Transaksi\Paid;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $pasang_bulan_ini = Carbon::now()->addMonth(-0)->format('m');
        $pasang_bulan_lalu = Carbon::now()->addMonth(-1)->format('m');
        $pasang_3_bulan_lalu = Carbon::now()->addMonth(-2)->format('m');

        $data['data_bulan'] = $request->query('data_bulan');
        $data['data_inv'] = $request->query('data_inv');
        // dd($data['data_bulan']);

        $query = Invoice::where('invoices.inv_status', '=', 'UNPAID');

        if ($data['data_bulan'] == "PELANGGAN BARU")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_ini);
        elseif ($data['data_bulan'] == "PELANGGAN 2 BULAN")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_lalu);
        elseif ($data['data_bulan'] == "PELANGGAN 3 BULAN")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_3_bulan_lalu);

        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->get();
        $data['inv_belum_lunas'] = $query->where('invoices.inv_status', '=', 'UNPAID')->sum('inv_total');
        $data['inv_count_unpaid'] = $query->where('invoices.inv_status', '=', 'UNPAID')->count();
        $data['inv_count_suspend'] = $query->where('invoices.inv_status', '=', 'SUSPEND')->count();
        return view('Transaksi/list_invoice', $data);
    }
    public function paid()
    {
        $invoice = Invoice::where('invoices.inv_status', '=', 'PAID');
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->addMonth(-0)->format('m');
        $data['data_invoice'] = $invoice->get();
        $data['inv_count_bulan'] = $invoice->whereMonth('inv_tgl_bayar', '=', $month)->count();
        // $data['inv_bulan'] = $invoice->sum('inv_total');
        $data['inv_bulan'] = $invoice->whereMonth('inv_tgl_bayar', '=', $month)->sum('inv_total');
        $data['inv_hari'] = $invoice->whereDay('inv_tgl_bayar', '=', $day)->sum('inv_total');
        // dd($data['inv_hari']);
        return view('Transaksi/list_invoice_paid', $data);
    }

    public function sub_invoice($id)
    {
        $data['invoice'] = Invoice::join('input_data', 'input_data.id', '=', 'invoices.inv_idpel')
            ->where('inv_id', $id)->first();
        $data['deskripsi'] = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')->where('invoices.inv_id', $id)->get();


        $data['sumharga'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_total');
        $data['sumppn'] = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn');
        $data['ppnj'] = env('PPN');
        $data['akun'] = SettingAkun::all();
        $data['ppn'] = SettingBiaya::first();


        return view('Transaksi/subinvoice', $data);
    }
    public function addDiskon(Request $request, $id)
    {
        // return response()->json($id);
        $data['inv_diskon'] = $request->diskon;
        $data['inv_total'] = $request->total;
        Invoice::where('inv_id', $id)->update($data);
        return response()->json($data);
    }
    public function payment(Request $request, $id)
    {

        $cek_inv = Laporan::where('lap_inv', $id)->first();
        if ($cek_inv) {
            $notifikasi = array(
                'pesan' => 'Invoice telah terbayar pada laporan admin',
                'alert' => 'error',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
        } else {

            //         $kredit = laporanharian::where('lh_admin', $id)->where('lh_status', 0)->sum('lh_kredit');
            //         $debet = laporanharian::where('lh_admin', $id)->where('lh_status', 0)->sum('lh_debet');
            //         $saldo_laporan_harian = $kredit - $debet;

            $admin_user = Auth::user()->id;
            $tgl = date('d-m-Y h:m:s');
            $tampil = Invoice::where('inv_id', $id)->first();

            $explode = explode('|', $request->transfer);
            if ($request->cabar == 'TUNAI') {
                $akun = '2';
                $norek = '-';
                $akun_nama = 'TUNAI';
                $biaya_adm =  0;
                $j_bayar = 0;
            } elseif ($request->cabar == 'TRANSFER') {
                $akun = $explode[0];
                $norek = $explode[1];
                $akun_nama = $explode[2];
                $biaya_adm =  $request->jumlah_bayar - $tampil->inv_total;
                $j_bayar = $request->jumlah_bayar;
            }



            $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
            $datas['inv_cabar'] = $request->cabar;
            $datas['inv_admin'] = $admin_user;
            $datas['inv_akun'] = $akun;
            $datas['inv_reference'] = '-';
            $datas['inv_payment_method'] = $akun_nama;
            $datas['inv_payment_method_code'] = $norek;
            $datas['inv_total_amount'] = $tampil->inv_total;
            $datas['inv_fee_merchant'] = 0;
            $datas['inv_fee_customer'] = 0;
            $datas['inv_total_fee'] = 0;
            $datas['inv_amount_received'] = 0;
            $datas['inv_tgl_bayar'] = $tgl_bayar;
            $datas['inv_status'] = 'PAID';
            Invoice::where('inv_id', $id)->update($datas);

            $data_lap['lap_id'] = 0;
            $data_lap['lap_tgl'] = $tgl_bayar;
            $data_lap['lap_inv'] = $id;
            $data_lap['lap_admin'] = $admin_user;
            $data_lap['lap_cabar'] = $request->cabar;
            $data_lap['lap_debet'] = 0;
            $data_lap['lap_kredit'] = $tampil->inv_total;
            $data_lap['lap_adm'] = $biaya_adm;
            $data_lap['lap_jumlah_bayar'] = $j_bayar;
            $data_lap['lap_keterangan'] = $tampil->inv_nama;
            $data_lap['lap_akun'] = $akun;
            $data_lap['lap_idpel'] = $tampil->inv_idpel;
            $data_lap['lap_jenis_inv'] = "INVOICE";
            $data_lap['lap_status'] = "-";
            $data_lap['lap_img'] = "-";

            Laporan::create($data_lap);

            // dd($data_lap);



            //         $sumppn = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_ppn'); #hitung total ppn invoice
            //         $sumharga = SubInvoice::where('subinvoice_id', $id)->sum('subinvoice_harga'); #hitung total harga invoice

            //         // dd($id);
            //         $tampil = (new GlobalController)->data_tagihan($id);

            //         $nohp = $tampil->hp;
            //         $nolayanan = $tampil->nolayanan;
            //         $diskon = $tampil->upd_diskon;
            //         $harga = number_format($tampil->subinvoice_harga);
            //         $jt_tempo = date($tampil->tgl_tagih  . '/m/Y', strtotime('+1 month'));


            //         $total_kredit = $sumharga + $sumppn - $diskon;
            //         $total_laporan_harian = $saldo_laporan_harian + $total_kredit;


            //         $paid['id_unpaid'] = $id; #No referensi transaksi. Contoh: T000100000000XHDFTR disini saya gunakan unpaid_id
            //         $paid['idpel_unpaid'] = $tampil->upd_idpel;
            //         $paid['reference'] = '';
            //         $paid['payment_method'] = $akun_nama; #Channel pembayaran. Contoh: BRI Virtual Account
            //         $paid['payment_method_code'] = $norek; #Kode channel pembayaran. Contoh: BRIVA
            //         $paid['total_amount'] = $total_kredit; #Jumlah pembayaran yang dibayar pelanggan
            //         $paid['fee_merchant'] = '0'; #Jumlah biaya yang dikenakan pada merchant
            //         $paid['fee_customer'] = '0'; #Jumlah biaya yang dikenakan pada customer
            //         $paid['total_fee'] = '0'; #Jumlah biaya fee_merchant + 
            //         $paid['amount_received'] = $total_kredit; #Jumlah bersih yang diterima merchant. Dihitung dari total_amount - (fee_merchant + fee_customer)
            //         $paid['is_closed_payment'] = '0'; #Tipe pembayaran
            //         $paid['status'] = 'PAID'; #Status transaksi
            //         $paid['paid_at'] = $tgl; #Timestamp waktu pembayaran sukses
            //         $paid['admin'] = $admin_user; #User Admin
            //         $paid['akun'] = $akun; #Cara Bayar
            //         $paid['note'] = ''; #Catatan

            //         Paid::create($paid);


            //         $lh['lh_id'] = $tampil->upd_id;
            //         $lh['lh_admin'] = $admin_user;
            //         $lh['lh_deskripsi'] = 'Invoice ' . $tampil->upd_id . ' ( ' . $tampil->nama . ' ) Diskon ' . number_format($diskon) . ' PPN ' . number_format($sumppn);
            //         $lh['lh_qty'] = '1';
            //         $lh['lh_debet'] = '0';
            //         $lh['lh_kredit'] = $total_kredit;
            //         $lh['lh_saldo'] = $total_laporan_harian;
            //         $lh['lh_status'] = '0';
            //         $lh['lh_akun'] = $akun;
            //         $lh['lh_kategori'] = 'PEMBAYARAN';
            //         laporanharian::create($lh);

            //         Invoice::where('upd_id', $id)->update([
            //             'upd_status' => 'PAID',
            //         ]);

            //         $idi = rand(10000, 99999);
            //         $pesan['id'] = $idi;
            //         $pesan['status'] = 'Pembayaran';
            //         $pesan['hp'] = $nohp;
            //         $pesan['pesan'] = "Terima kasih 🙏
            // Pembayaran invoice sudah kami terima
            // *************************
            // No.Layanan : $nolayanan
            // Pelanggan : $tampil->nama
            // Invoice : *$id*
            // Paket : $tampil->paket_nama
            // Total : *$harga*
            // Channel : $akun_nama
            // Tanggal lunas : $tgl
            // Layanan sudah aktif dan dapat digunakan sampai dengan *$jt_tempo*
            // *************************
            // --------------------
            // Pesan ini bersifat informasi dan tidak perlu dibalas
            // *OVALL FIBER*";
            //         Pesan::create($pesan);
            //         // dd($pesan);
            //         (new WhatsappController)->wa_pembayaran($id);

            $notifikasi = array(
                'pesan' => 'Berhasil melakukan pembayaran',
                'alert' => 'success',
            );
            return redirect()->route('admin.inv.sub_invoice', ['id' => $id])->with($notifikasi);
        }
    }
    public function addons(Request $request, $id)
    {
        $unp = Invoice::where('inv_id', $id)->first();
        // dd($id);
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
}
