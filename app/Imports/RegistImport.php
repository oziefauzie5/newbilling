<?php

namespace App\Imports;

use App\Models\PSB\Registrasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class RegistImport implements ToModel
{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
           return new Registrasi([
            'reg_idpel'=>$row[0],
            'corporate_id' =>Session::get('corp_id'),
            'reg_nolayanan'=>$row[1],
            'reg_layanan'=>$row[2],
            'reg_profile'=>$row[3],
            'reg_jenis_tagihan'=>$row[4],
            'reg_harga'=>$row[5],
            'reg_kode_unik'=>$row[6],
            'reg_ppn'=>$row[7],
            'reg_bph_uso'=>0,
            'reg_username'=>$row[8],
            'reg_password'=>$row[9],
            'reg_tgl_pasang'=>$row[10], 
            'reg_tgl_tagih'=>$row[11], 
            'reg_tgl_jatuh_tempo'=>$row[12], 
            'reg_tgl_deaktivasi'=>$row[13],
            'reg_catatan'=>$row[14],
            'reg_status'=>$row[15],
            'reg_progres'=>$row[16],
            'reg_inv_control'=>$row[17],
            'reg_img'=>$row[18],
            

        ]);
    }
}
