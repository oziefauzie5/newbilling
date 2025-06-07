<?php

namespace App\Imports;

use App\Models\Gudang\Data_BarangKeluar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangKeluarImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new Data_BarangKeluar([
            'corporate_id' =>Session::get('corp_id'),
            'bk_jenis_laporan' => $row[0],
            'bk_id_barang' => $row[1],
            'bk_idpel' => $row[2],
            'bk_kategori' => $row[3],
            'bk_harga' => $row[4],
            'bk_before' => $row[5],
            'bk_after' => $row[6],
            'bk_terpakai' => $row[7],
            'bk_jumlah' => $row[8],
            'bk_keperluan' => $row[9],
            'bk_file_bukti' => $row[10],
            'bk_nama_pengguna' => $row[11],
            'bk_waktu_keluar' => $row[12],
            'bk_admin_input' => $row[13],
            'bk_penerima' => $row[14],
            'bk_status' => $row[15],
            'bk_keterangan' => $row[16],
            'created_at' => $row[17],
            'updated_at' => $row[18],
        ]);
    }
}


