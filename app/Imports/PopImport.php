<?php

namespace App\Imports;

use App\Models\Teknisi\Data_pop;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class PopImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Data_pop([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'data__site_id' =>$row[1],
            'pop_nama' => $row[2],
            'pop_alamat' => $row[3],
            'pop_koordinat' => $row[4],
            'pop_file_topologi' => $row[5],
            'pop_status' => $row[6],

        ]);
    }
}
