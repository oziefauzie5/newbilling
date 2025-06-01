<?php

namespace App\Exports;

use App\Models\PSB\Registrasi;
use Maatwebsite\Excel\Concerns\FromCollection;

class RegistExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return Registrasi::all();
    }
}
