<?php

namespace App\Exports;

use App\Models\PSB\InputData;
use Maatwebsite\Excel\Concerns\FromCollection;

class InputDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return InputData::all();
    }
}
