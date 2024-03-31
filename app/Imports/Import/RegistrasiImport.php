<?php

namespace App\Imports\Import;

use App\Models\PSB\Registrasi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RegistrasiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $date = Date::excelToDateTimeObject($row[1]);

        // $tgl = date('Y-m-d', strtotime($date));


        $data['reg_idpel'] = $row[0];
        $data['reg_nolayanan'] = $row[1];
        $data['reg_layanan'] = $row[2];
        $data['reg_profile'] = $row[3];
        $data['reg_jenis_tagihan'] = $row[4];
        $data['reg_harga'] = $row[5];
        $data['reg_kode_unik'] = $row[6];
        $data['reg_deposit'] = $row[7];
        $data['reg_ppn'] = $row[8];
        $data['reg_dana_kas'] = $row[9];
        $data['reg_dana_kerjasama'] = $row[10];
        $data['reg_username'] = $row[11];
        $data['reg_password'] = $row[12];
        $data['reg_tgl_pasang'] = $row[13];
        $data['reg_tgl_jatuh_tempo'] = $row[14];
        $data['reg_tgl_tagih'] = $row[15];
        $data['reg_wilayah'] = $row[16];
        $data['reg_fat'] = $row[17];
        $data['reg_fat_opm'] = $row[18];
        $data['reg_home_opm'] = $row[19];
        $data['reg_los_opm'] = $row[20];
        $data['reg_router'] = $row[21];
        $data['reg_mrek'] = $row[22];
        $data['reg_mac'] = $row[23];
        $data['reg_sn'] = $row[24];
        $data['reg_kode_pactcore'] = $row[25];
        $data['reg_kode_ont'] = $row[26];
        $data['reg_kode_adaptor'] = $row[27];
        $data['reg_kode_dropcore'] = $row[28];
        $data['reg_before'] = $row[29];
        $data['reg_after'] = $row[30];
        $data['reg_penggunaan_dropcore'] = $row[31];
        $data['reg_ip_address'] = $row[32];
        $data['reg_catatan'] = $row[33];
        $data['reg_progres'] = $row[34];
        $data['reg_stt_perangkat'] = $row[35];
        $data['reg_slotonu'] = $row[36];
        $data['reg_teknisi_team'] = $row[37];

        $inv['inv_id'] = rand(10000, 19999);
        $inv['inv_status'] = 'UNPAID';
        $data['reg_tgl_jatuh_tempo'] = $row[14];

        $data['tgl'] = Carbon::now()->toDateString();
        $data['tgl2'] =  Carbon::create($data['tgl'])->toDateString();

        dd($data);

        // $inv['inv_tgl_isolir'] = $inv_tgl_isolir1blan;
        // $inv['inv_total'] = $tagihan_tanpa_ppn  + $query->reg_ppn;
        // $inv['inv_tgl_tagih'] = $tanggal;
        // $inv['inv_tgl_jatuh_tempo'] = $tanggal;
        // $inv['inv_periode'] = $periode1blan;
        // $inv['inv_idpel'] = $query->reg_idpel;
        // $inv['inv_nolayanan'] = $query->reg_nolayanan;
        // $inv['inv_nama'] = $query->input_nama;
        // $inv['inv_jenis_tagihan'] = $query->reg_jenis_tagihan;
        // $inv['inv_profile'] = $query->paket_nama;
        // $inv['inv_mitra'] = 'SYSTEM';
        // $inv['inv_kategori'] = 'OTOMATIS';
        // $inv['inv_diskon'] = '0';
        // $inv['inv_note'] = $query->input_nama;


        // $sub_inv['subinvoice_id'] = $inv['inv_id'];
        // $sub_inv['subinvoice_harga'] = $query->reg_harga;
        // $sub_inv['subinvoice_ppn'] = $query->reg_ppn;
        // $sub_inv['subinvoice_total'] = $inv['inv_total'];
        // $sub_inv['subinvoice_qty'] = '1';
        // $sub_inv['subinvoice_deskripsi'] = $query->paket_nama . ' ( ' . $inv['inv_periode'] . ' )';
        // $sub_inv['subinvoice_status'] = '0';
        // return new Registrasi($data);
    }
}
