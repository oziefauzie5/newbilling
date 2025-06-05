<?php

namespace App\Imports;

use App\Models\Transaksi\SubInvoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class SubInvoiceImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new SubInvoice([
            'corporate_id' =>Session::get('corp_id'),
            'subinvoice_id' =>$row[0],
            'subinvoice_deskripsi' =>$row[0],
            'subinvoice_qty' =>$row[0],
            'subinvoice_harga' =>$row[0],
            'subinvoice_ppn' =>$row[0],
            'subinvoice_bph_uso' =>$row[0],
            'subinvoice_total' =>$row[0],
            'subinvoice_status'=>$row[0],

        ]);
    }
}
