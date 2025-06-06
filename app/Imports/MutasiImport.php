<?php

namespace App\Imports;

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
        return new Teknisi([
            'corporate_id' =>Session::get('corp_id'),
            'user_id',
            'teknisi_team',
            'teknisi_ket',
            'teknisi_job',
            'teknisi_idpel',
            'teknisi_psb',
            'teknisi_status',

        ]);
    }
}