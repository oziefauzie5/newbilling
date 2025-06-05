<?php

namespace App\Imports;

use App\Models\PSB\FtthInstalasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class FtthInstalasiImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new FtthInstalasi([
            'corporate_id' =>Session::get('corp_id'),
            'data__odp_id' => $row[0],#
            'reg_noc' => 2,
            'reg_in_ont' => $row[1],#
            'reg_router' => $row[2],#
            'reg_slot_odp' => $row[3],#
        ]);
    }
}
