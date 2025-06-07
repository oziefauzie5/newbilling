<?php

namespace App\Imports;

use App\Models\PSB\InputData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class InputDataImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new InputData([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'data__site_id' =>1,
            'input_tgl' =>$row[1],
            'input_nama' =>$row[2],
            'input_ktp' =>$row[3],
            'input_hp' =>$row[4],
            'input_hp_2' =>$row[5],
            'input_email' =>$row[6],
            'input_alamat_ktp' =>$row[7],
            'input_alamat_pasang' =>$row[8],
            'input_sales' =>$row[9],
            'input_subseles' =>$row[10],
            'password' =>$row[11],
            'input_maps' =>$row[12],
            'input_koordinat' =>$row[13],
            'input_status' =>$row[14],
            'input_keterangan' =>$row[15],
            'created_at' =>$row[16],
            'updated_at' =>$row[17],
            'input_id_baru' =>$row[18],

        ]);
    }
}
