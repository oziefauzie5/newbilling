<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'mutasi_sales_mitra_id',
        'mutasi_sales_tgl_transaksi',
        'mutasi_sales_idpel',
        'mutasi_sales_admin',
        'mutasi_sales_type',
        'mutasi_sales_jumlah',
        'mutasi_sales_deskripsi',
        'mutasi_sales_inv_id',
    ];
}
