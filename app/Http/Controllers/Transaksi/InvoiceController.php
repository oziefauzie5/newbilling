<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAkun;
use App\Models\Applikasi\SettingBiaya;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\Applikasi\SettingWhatsapp;
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

        $query = Invoice::where('inv_status', '!=', 'PAID');

        if ($data['data_bulan'] == "PELANGGAN BARU")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_ini);
        elseif ($data['data_bulan'] == "PELANGGAN 2 BULAN")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_bulan_lalu);
        elseif ($data['data_bulan'] == "PELANGGAN 3 BULAN")
            $query->whereMonth('inv_tgl_pasang', '=', $pasang_3_bulan_lalu);

        $data['inv_count_all'] = $query->count();
        $data['data_invoice'] = $query->get();
        $data['inv_count_unpaid'] = Invoice::where('inv_status', '=', 'UNPAID')->count();
        $data['inv_belum_lunas'] = Invoice::where('inv_status', '!=', 'PAID')->sum('inv_total');
        $data['inv_lunas'] = Invoice::where('inv_status', '=', 'PAID')->sum('inv_total');
        $data['inv_count_suspend'] = Invoice::where('inv_status', '=', 'SUSPEND')->count();
        $data['inv_count_isolir'] = Invoice::where('inv_status', '=', 'ISOLIR')->count();
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
        $data['deskripsi'] = Invoice::join('sub_invoices', 'sub_invoices.subinvoice_id', '=', 'invoices.inv_id')
            ->where('invoices.inv_id', $id)->get();


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
            $data_pelanggan = Invoice::join('registrasis', 'registrasis.reg_idpel', '=', 'invoices.inv_idpel')
                ->join('pakets', 'pakets.paket_id', '=', 'registrasis.reg_profile')
                ->where('inv_id', $id)
                ->first();

            $tgl_bayar = date('Y-m-d', strtotime(Carbon::now()));
            $cek_trx = Transaksi::whereDate('created_at', $tgl_bayar)->where('trx_kategori', 'INVOICE')->first();
            #inv0 = Jika Sambung dari tanggal isolir, maka pemakaian selama isolir tetap dihitung kedalam invoice
            #inv1 = Jika Sambung dari tanggal bayar, maka pemakaian selama isolir akan diabaikan dan dihitung kembali mulai dari semanjak pembayaran
            $inv0_tagih = Carbon::create($data_pelanggan->reg_tgl_tagih)->addMonth(1)->toDateString();
            $inv0_tagih0 = Carbon::create($inv0_tagih)->addDay(-2)->toDateString();
            $inv0_jt_tempo = Carbon::create($data_pelanggan->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString();
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
                $biaya_adm =  $request->jumlah_bayar - $data_pelanggan->inv_total;
                $j_bayar = $request->jumlah_bayar;
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
            $datas['inv_status'] = 'PAID';
            Invoice::where('inv_id', $id)->update($datas);

            $data_lap['lap_id'] = 0;
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

            if ($cek_trx) {
                $data_trx['trx_admin'] = 'SYSTEM';
                $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                $data_trx['trx_qty'] = $cek_trx->trx_qty + 1;
                $data_trx['trx_total'] = $cek_trx->trx_total + $data_pelanggan->inv_total;
                Transaksi::whereDate('created_at', $tgl_bayar)->update($data_trx);
            } else {
                $data_trx['trx_admin'] = 'SYSTEM';
                $data_trx['trx_deskripsi'] = 'Pembayaran Invoice';
                $data_trx['trx_qty'] = 1;
                $data_trx['trx_total'] = $data_pelanggan->inv_total;
                Transaksi::whereDate('created_at', $tgl_bayar)->create($data_trx);
            }

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
                        'profile' => $data_pelanggan->paket_nama,
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
                        'comment' =>  $reg['reg_tgl_jatuh_tempo'] == '' ? '' : $reg['reg_tgl_jatuh_tempo'],
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
    public function suspand_otomatis()
    {
        $users_teknisi = User::select('model_has_roles.*', 'roles.*', 'users.*', 'users.name as nama_teknisi')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.id', '11')
            ->get();

        foreach ($users_teknisi as $t) {
            echo $t->nama_teknisi . '<br>';
        }
        // $cek_pesan = Pesan::where('status', '0')->count();
        // if ($cek_pesan) {
        //     $whatsapp = SettingWhatsapp::where('wa_nama', 'CUSTUMER SERVICE')->where('wa_status', 'Enable')->first();
        //     $pesan = Pesan::where('status', '0')->first();
        //     $body = array(
        //         "api_key" => $whatsapp->wa_key,
        //         "target" => $pesan->target,
        //         "data" => array("message" => $pesan->pesan)
        //     );
        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => $whatsapp->wa_url . "/send",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => array(
        //             'target' => $pesan->target,
        //             'message' => $pesan->pesan,
        //             // 'url' => 'https://md.fonnte.com/images/wa-logo.png',
        //             // 'filename' => 'filename',
        //             // 'schedule' => 0,
        //             // 'typing' => false,
        //             // 'delay' => '5',
        //             'countryCode' => '62',
        //             // 'file' => new CURLFile("localfile.jpg"),
        //             'location' => '-6.60857950392001, 106.755854',
        //             // 'followup' => 0,
        //         ),
        //         CURLOPT_HTTPHEADER => array(
        //             'Authorization: ' . $whatsapp->wa_key . ''
        //         ),
        //     ));

        //     $response = curl_exec($curl);
        //     $err = curl_error($curl);
        //     curl_close($curl);
        //     if ($err) {
        //         $mesage['status'] = 'Fail';
        //     } else {
        //         echo $response;
        //         $mesage['status'] = 'Done';
        //     }
        //     dd($mesage['status']);
        //     // Pesan::where('id', $pesan->id)->update($mesage);
        // }
    }
}
