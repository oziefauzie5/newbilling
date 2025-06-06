<?php

namespace App\Imports;

use App\Models\Transaksi\Laporan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class LaporanImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Laporan([
            'corporate_id' =>Session::get('corp_id'),
            'lap_id'=> $row[0],
            'lap_tgl'=> $row[1],
            'lap_inv'=> $row[2],
            'lap_admin'=> $row[3],
            'lap_pokok'=> $row[4],
            'lap_fee_mitra'=> $row[5],
            'lap_ppn'=> $row[6],
            'lap_bph_uso'=> 0,
            'lap_jumlah'=> $row[7],
            'lap_akun'=> $row[8],
            'lap_keterangan'=> $row[9],
            'lap_jenis_inv'=> $row[10],
            'lap_status'=> $row[11],
            'lap_img'=> $row[12],
            'created_at'=> $row[13],
            'updated_at'=> $row[14],

        ]);
    }
}