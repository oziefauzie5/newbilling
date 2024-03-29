<?php

namespace App\Imports\Import;

use App\Models\InputData;
use App\Models\PSB\InputData as PSBInputData;
use Illuminate\Support\Facades\Hash;
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
        // dd(Hash::make('0' . $row[4]));
        return new PSBInputData([

            'id' => $row[0],
            'input_nama' => $row[1],
            'input_ktp' => $row[2],
            'input_hp' => $row[3],
            'input_email' => $row[4],
            'input_alamat_ktp' => $row[5],
            'input_alamat_pasang' => $row[6],
            'input_sales' => $row[7],
            'input_subseles' => $row[8],
            'input_password' =>  Hash::make('0' . $row[3]),
            'input_maps' => $row[9],
            'input_kordinat' => $row[10],
            'input_status' => $row[11],
            'input_keterangan' => $row[12],
        ]);
    }
}
