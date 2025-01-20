<?php

namespace App\Models\Tiket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_SubTiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'subtiket_id',
        'subtiket_kode_barang',
        'subtiket_jenis_barang',
        'subtiket_jumlah_barang',
    ];
}
