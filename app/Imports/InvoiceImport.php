<?php

namespace App\Imports;

use App\Models\Transaksi\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class InvoiceImport implements ToModel
{
    /**
    * @param Collection $collection
    */
     public function model(array $row)
    {
        return new Invoice([
            'corporate_id' =>Session::get('corp_id'),
            'inv_id' =>$row[0],
            'inv_status' =>$row[1],
            'inv_idpel' =>$row[2],
            'inv_nolayanan'=>$row[3],
            'inv_tgl_tagih'=>$row[4],
            'inv_tgl_jatuh_tempo'=>$row[5],
            'inv_tgl_isolir'=>$row[6],
            'inv_tgl_bayar'=>$row[7],
            'inv_periode'=>$row[8],
            'inv_diskon'=>$row[9],#0
            'inv_total'=>$row[10],
            'inv_admin'=>$row[11],
            'inv_cabar'=>$row[12],
            'inv_akun'=>$row[13],
            'inv_reference'=>$row[14],
            'inv_payment_method'=>$row[15],
            'inv_payment_method_code'=>$row[16],
            'inv_total_amount'=>$row[17],
            'inv_fee_merchant'=>$row[18],
            'inv_fee_customer'=>$row[19],
            'inv_total_fee'=>$row[20],#
            'inv_amount_received'=>$row[21],#
            'inv_note'=>$row[22],
            'inv_bukti_bayar'=>$row[23],
            'created_at'=>$row[24],
            'updated_at'=>$row[25],

        ]);
    }
}
  
