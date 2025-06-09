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
            'data__site_id'=>$row[0],
            'tiket_corporate_id'=>$row[0],
            'barang_id_group'=>$row[0],
            'tiket_pending'=>$row[0],
            'tiket_pembuat'=>$row[0],
            'tiket_kode'=>$row[0],
            'tiket_idpel'=>$row[0],
            'tiket_jenis'=>$row[0],
            'tiket_nama'=>$row[0],
            'tiket_type'=>$row[0],
            'tiket_jadwal_kunjungan'=>$row[0],
            'tiket_waktu_mulai'=>$row[0],
            'tiket_waktu_selesai'=>$row[0],
            'tiket_foto'=>$row[0],
            'tiket_kendala'=>$row[0],
            'tiket_tindakan'=>$row[0],
            'tiket_keterangan'=>$row[0],
            'tiket_teknisi1'=>$row[0],
            'tiket_teknisi2'=>$row[0],
            // 'tiket_barang'=>$row[0],
            'tiket_status'=>$row[0],
            'tiket_idbarang_keluar'=>$row[0],
        ]);
    }
}
