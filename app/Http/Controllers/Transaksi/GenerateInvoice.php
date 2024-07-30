<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingWaktuTagihan;
use App\Models\PSB\Registrasi;
use App\Models\Transaksi\Invoice;
use App\Models\Transaksi\SubInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateInvoice extends Controller
{
    public function generate_invoice()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'reg_profile')
            ->where('reg_progres', 5)
            // ->where('reg_status', '=', 'PAID')
            ->get();

        // dd($data_pelanggan);
        $swaktu = SettingWaktuTagihan::first();
        $i = 1;
        foreach ($data_pelanggan as $dp) {
            // $cek_invoice = Invoice::where('inv_idpel', $dp->reg_idpel)->where('inv_status', 'UNPAID')->get();
            // dd($cek_invoice);
            if ($dp->reg_status == 'PAID') {
                $inv_id = rand(1000, 1999) . $i++;
                $hari_jt_tempo = date('d', strtotime($dp->reg_tgl_jatuh_tempo));
                $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($swaktu->wt_jeda_tagihan_pertama)->toDateString();


                Invoice::create([
                    'inv_id' => $inv_id,
                    'inv_status' => 'UNPAID',
                    'inv_idpel' => $dp->reg_idpel,
                    'inv_nolayanan' => $dp->reg_nolayanan,
                    'inv_nama' => $dp->input_nama,
                    'inv_jenis_tagihan' => $dp->reg_jenis_tagihan,
                    'inv_profile' => $dp->paket_nama,
                    'inv_mitra' => 'SYSTEM',
                    'inv_kategori' => 'OTOMATIS',
                    'inv_tgl_tagih' => $hari_tgl_tagih,
                    'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
                    'inv_tgl_isolir' => $tgl_isolir,
                    'inv_periode' => $periode1blan,
                    'inv_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                ]);

                SubInvoice::create(
                    [
                        'subinvoice_id' => $inv_id,
                        'subinvoice_deskripsi' => $dp->paket_nama . ' ( ' . $periode1blan . ' )',
                        'subinvoice_harga' => $dp->reg_harga + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                        'subinvoice_ppn' => $dp->reg_ppn,
                        'subinvoice_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                        'subinvoice_qty' => 1,
                        'subinvoice_status' => 0,
                    ]
                );
            } else {

                $data_invoice = Invoice::where('inv_idpel', $dp->reg_idpel)->where('inv_status', '!=', 'PAID')->get();

                foreach ($data_invoice as $inv) {
                    $inv_id = $inv->inv_id;
                    $hari_jt_tempo = date('d', strtotime($dp->reg_tgl_jatuh_tempo));
                    $hari_tgl_tagih = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_tagih));
                    $periode1blan = date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($year . '-' . $month . '-' . $hari_jt_tempo)->addMonth(1)->toDateString()));
                    $tgl_jt_tempo = date($year . '-' . $month . '-d', strtotime($dp->reg_tgl_jatuh_tempo));
                    $tgl_isolir =  Carbon::create($tgl_jt_tempo)->addDay($swaktu->wt_jeda_tagihan_pertama)->toDateString();

                    Invoice::where('inv_id', $inv->inv_id)->update([
                        'inv_status' => 'UNPAID',
                        'inv_idpel' => $dp->reg_idpel,
                        'inv_nolayanan' => $dp->reg_nolayanan,
                        'inv_nama' => $dp->input_nama,
                        'inv_jenis_tagihan' => $dp->reg_jenis_tagihan,
                        'inv_profile' => $dp->paket_nama,
                        'inv_mitra' => 'SYSTEM',
                        'inv_kategori' => 'OTOMATIS',
                        'inv_tgl_tagih' => $hari_tgl_tagih,
                        'inv_tgl_jatuh_tempo' => $tgl_jt_tempo,
                        'inv_tgl_isolir' => $tgl_isolir,
                        'inv_periode' => $periode1blan,
                        'inv_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                    ]);
                    SubInvoice::where('subinvoice_id', $inv->inv_id)->update(
                        [
                            'subinvoice_id' => $inv_id,
                            'subinvoice_deskripsi' => $dp->paket_nama . ' ( ' . $periode1blan . ' )',
                            'subinvoice_harga' => $dp->reg_harga + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                            'subinvoice_ppn' => $dp->reg_ppn,
                            'subinvoice_total' => $dp->reg_harga + $dp->reg_ppn + $dp->reg_kode_unik + $dp->reg_dana_kas + $dp->reg_dana_kerjasama,
                            'subinvoice_qty' => 1,
                            'subinvoice_status' => 0,
                        ]
                    );
                }
            }
        }
        $notifikasi = array(
            'pesan' => 'Generate Berhasil',
            'alert' => 'success',
        );
        return redirect()->route('admin.inv.index')->with($notifikasi);
    }
}
