<?php

namespace App\Imports\Import;

use App\Models\PSB\InputData ;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class InputDataImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
    
        // dd('ozi');
        return new InputData([

            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'data__site_id' =>$row[1],
            'input_tgl' =>$row[2],
            'input_nama' =>$row[3],
            'input_ktp' =>$row[4],
            'input_hp' =>$row[5],
            'input_hp_2' =>$row[6],
            'input_email' =>$row[7],
            'input_alamat_ktp' =>$row[8],
            'input_alamat_pasang' =>$row[9],
            'input_sales' =>$row[10],
            'input_subseles' =>$row[11],
            'password' =>$row[12],
            'input_maps' =>$row[13],
            'input_koordinat' =>$row[14],
            'input_status' =>$row[15],
            'input_keterangan' =>$row[16],

        ]);
    }
}
