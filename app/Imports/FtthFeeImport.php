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
            'corporate_id' =>Session::get('corp_id'),
            'fee_idpel'=> $row[0],
            'reg_mitra'=> $row[1],
            'reg_fee'=> $row[2],
        ]);
    }
}