<?php

namespace App\Imports;

use App\Models\Gudang\Data_Kategori;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class KategoriImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new Data_Kategori([
            'corporate_id' =>Session::get('corp_id'),
            'id_kategori'=>$row[0],
            'nama_kategori'=>$row[1],
            'jenis_jurnal_kategori'=>$row[2],
            'kategori_satuan'=>$row[3],
            'kategori_qty'=>$row[4],
            'status_kategori'=>$row[5],
        ]);
    }
}

