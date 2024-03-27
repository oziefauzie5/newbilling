<?php

namespace App\Imports\Import;

use App\Models\InputData;
use App\Models\PSB\InputData as PSBInputData;
use Maatwebsite\Excel\Concerns\ToModel;

class InputDataImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        return new PSBInputData([
            'id' =>  $row[0],
            'input_nama' =>  $row[2],
            'input_ktp' =>  $row[3],
            'input_hp' =>  $row[4],
            'input_alamat' =>  $row[5],
            'input_alamat_pasang' =>  $row[5],
        ]);
    }
}
