<?php

namespace App\Imports;

use App\Models\Mitra\MitraSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class MitraImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new MitraSetting([
            'corporate_id' =>Session::get('corp_id'),
            'data__site_id' =>1,
            'mts_user_id' =>$row[0],
            'mts_limit_minus' =>$row[1],
            'mts_komisi' =>$row[2],
            'mts_wilayah' =>$row[3],
            'created_at' =>$row[4],
            'updated_at' =>$row[5],
        ]);
    }
}
