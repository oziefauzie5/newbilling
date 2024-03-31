<?php

namespace App\Imports\Import;

use App\Models\InputData;
use App\Models\PSB\InputData as PSBInputData;
use Illuminate\Support\Facades\Hash;
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
        // $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]);
        $date = Date::excelToDateTimeObject($row[1]);
        // $tgl = date('Y-m-d', strtotime($date));
        // dd($tgl);
        return new PSBInputData([
            'id' => $row[0],
            'input_tgl' => $date,
            'input_nama' => $row[2],
            'input_ktp' => $row[3],
            'input_hp' => $row[4],
            'input_email' => $row[5],
            'input_alamat_ktp' => $row[6],
            'input_alamat_pasang' => $row[7],
            'input_sales' => $row[8],
            'input_subseles' => $row[9],
            'input_password' => '0' . $row[4],
            'input_maps' => $row[10],
            'input_kordinat' => $row[11],
            'input_status' => $row[12],
            'input_keterangan' => $row[13],
        ]);
    }
}
