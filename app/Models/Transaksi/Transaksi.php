<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'trx_kategori',
        'trx_jenis',
        'trx_admin',
        'trx_deskripsi',
        'trx_qty',
        'trx_kredit',
        'trx_debet',
        'trx_saldo',
    ];
}
