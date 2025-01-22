<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Keranjang extends Model
{
    use HasFactory;
    protected $fillable = [
        'keranjang_id_admin',
        'keranjang_id_barang',
    ];
}
