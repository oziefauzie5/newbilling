<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Keranjang extends Model
{
    use HasFactory;
    protected $fillable = [
        'keranjang_id',
        'keranjang_kategori',
        'keranjang_id_barang',
        'keranjang_id_barang',
    ];
}
