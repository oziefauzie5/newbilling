<?php

namespace App\Imports;

use App\Models\Teknisi\Data_Odc;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OdcImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Data_Odc([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'data__olt_id'=>$row[1],
            'odc_pon_olt'=>$row[2],
            'odc_core'=>$row[3],
            'odc_nama'=>$row[4],
            'odc_jumlah_port'=>$row[5],
            'odc_file_topologi'=>$row[6],
            'odc_koordinat'=>$row[7],
            'odc_lokasi_img'=>$row[8],
            'odc_keterangan'=>$row[9],
            'odc_status'=>$row[10],

        ]);
    }
}
