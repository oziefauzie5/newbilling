<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'trx_kategori',
        'trx_admin',
        'trx_deskripsi',
        'trx_qty',
        'trx_total',
    ];
}
