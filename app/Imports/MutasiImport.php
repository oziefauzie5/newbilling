<?php

namespace App\Imports;

use App\Models\Mitra\Mutasi;
use App\Models\Teknisi\Teknisi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class MutasiImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Mutasi([
            'corporate_id' =>Session::get('corp_id'),
            'id' => $row[0],
            'mt_mts_id' => $row[1],
            'mt_admin' => $row[2],
            'mt_kategori' => $row[3],
            'mt_deskripsi' => $row[4],
            'mt_cabar' => $row[5],
            'mt_kredit' => $row[6],
            'mt_debet' => $row[7],
            'mt_saldo' => $row[8],
            'mt_biaya_adm' => $row[9],
            'mt_status' => $row[10],
            'created_at' => $row[11],
            'updated_at' => $row[12],
    ]);
    }

}