<?php

namespace App\Imports;

use App\Models\Tiket\Data_Tiket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class TiketImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new Data_Tiket([
            'corporate_id' =>Session::get('corp_id'),
            'tiket_id'=>$row[0],
            'tiket_idbarang_keluar'=>$row[1],
            'tiket_pending'=>$row[2],
            'tiket_pembuat'=>$row[3],
            'tiket_idpel'=>$row[4],
            'data__site_id'=>$row[5],
            'tiket_jenis'=>$row[6],
            'tiket_nama'=>$row[7],
            'tiket_type'=>$row[8],
            'tiket_jadwal_kunjungan'=>$row[9],
            'tiket_waktu_mulai'=>$row[10],
            'tiket_waktu_selesai'=>$row[11],
            'tiket_foto'=>$row[12],
            'tiket_kendala'=>$row[13],
            'tiket_tindakan'=>$row[14],
            'tiket_keterangan'=>$row[15],
            'tiket_teknisi1'=>$row[16],
            'tiket_teknisi2'=>$row[17],
            'tiket_status'=>$row[18],
            'created_at' =>$row[19],
            'updated_at' =>$row[20],
        ]);
    }
}
