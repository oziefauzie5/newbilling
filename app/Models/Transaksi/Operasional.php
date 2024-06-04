<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operasional extends Model
{
    use HasFactory;
    protected $fillable = [
        'op_id',
        'op_tgl',
        'op_uraian',
        'op_kategori',
        'op_admin',
        'op_metode_bayar',
        'op_debet',
        'op_kredit',
        'op_status',
    ];
}
