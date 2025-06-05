<?php

namespace App\Imports;

use App\Models\PSB\FtthFee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class FtthFeeImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new FtthFee([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'fee_idpel'=> $row[1],
            'reg_mitra'=> $row[3],
            'reg_fee'=> $row[2],

        ]);
    }
}