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
        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'reg_idpel')
            ->join('pakets', 'pakets.paket_id', '=', 'reg_profile')
            ->where('reg_progres', 5)->get();
        $swaktu = SettingWaktuTagihan::first();
        foreach ($data_pelanggan as $dp) {
            $inv_id = rand(10000, 19999);
            $periode1blan = date('d-m-Y', strtotime(Carbon::create($dp->reg_tgl_jatuh_tempo)->toDateString())) . ' - ' . date('d-m-Y', strtotime(Carbon::create($dp->reg_tgl_jatuh_tempo)->addMonth(1)->toDateString()));
            $tgl_isolir =  Carbon::create($dp->reg_tgl_jatuh_tempo)->addDay($swaktu->wt_jeda_tagihan_pertama)->toDateString();
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
                'inv_tgl_tagih' => $dp->reg_tgl_tagih,
                'inv_tgl_jatuh_tempo' => $dp->reg_tgl_jatuh_tempo,
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
        }
        dd($data_pelanggan);
    }
}
