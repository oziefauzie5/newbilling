<?php

namespace App\Imports;

use App\Models\Teknisi\Teknisi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class TeknisiImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Teknisi([
            'corporate_id' =>Session::get('corp_id'),
            'user_id' =>$row[0],
            'teknisi_team' =>$row[1],
            'teknisi_ket' =>$row[2],
            'teknisi_job' =>$row[3],
            'teknisi_idpel' =>$row[4],
            'teknisi_psb' =>$row[5],
            'teknisi_status' =>$row[6],
            'created_at' =>$row[7],
            'updated_at' =>$row[8],

        ]);
    }
}