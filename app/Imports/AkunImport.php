<?php

namespace App\Imports;

use App\Models\Applikasi\SettingAkun;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class AkunImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new SettingAkun([
            'id' =>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'akun_type'=>$row[1],
            'akun_nama'=>$row[2],
            'akun_rekening'=>$row[3],
            'akun_pemilik'=>$row[4],
            'akun_status'=>$row[5],
            'akun_kategori'=>$row[6],
            'created_at'=>$row[7],
            'updated_at'=>$row[8],
        ]);
}
}
