<?php

namespace App\Imports;

use App\Models\Teknisi\Data_Odp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OdpImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Data_Odp([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'data__odc_id' => $row[1],
            'odp_id' => $row[2],
            'odp_core' => $row[3],
            'odp_nama' => $row[4],
            'odp_jumlah_slot' => $row[5],
            'odp_lokasi_img' => $row[6],
            'odp_file_topologi' => $row[7],
            'odp_koordinat' => $row[8],
            'odp_keterangan' => $row[9],
            'odp_status' => $row[10],
            'odp_slot_odc' => $row[11],

        ]);
    }
}
