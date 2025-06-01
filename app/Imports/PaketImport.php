<?php

namespace App\Imports;

use App\Models\Router\Paket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class PaketImport  implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Paket([
            'paket_id'=> $row[0],
            'corporate_id' =>Session::get('corp_id'),
            'paket_nama' => $row[1],
            'paket_limitasi' => $row[2],
            'paket_shared' => $row[3],
            'paket_masa_aktif' => $row[4],
            'paket_komisi' => $row[5],
            'paket_harga' => $row[6],
            'paket_lokal' => $row[7],
            'paket_remote_address' => $row[8],
            'paket_layanan' => $row[9],
            'paket_mode' => $row[10],
            'paket_warna' => $row[11],
            'paket_status' => $row[12],

        ]);
    }
}