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
            'router_id' => $row[0],
            'olt_nama' => $row[0],
            'olt_pon' => $row[0],
            'olt_file_topologi' => $row[0],
            'olt_status' => $row[0],

        ]);
    }
}
