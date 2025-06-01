<?php

namespace App\Imports;

use App\Models\Teknisi\Data_Olt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OltImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Data_Olt([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'router_id' => $row[1],
            'olt_nama' => $row[2],
            'olt_pon' => $row[3],
            'olt_file_topologi' => $row[4],
            'olt_status' => $row[5],

        ]);
    }
}
