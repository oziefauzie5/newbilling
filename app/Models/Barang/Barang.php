<?php

namespace App\Models\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_barang',
        'id_trx',
        'id_supplier',
        'barang_kategori',
        'barang_nama',
        'barang_masuk',
        'barang_keluar',
        'barang_harga',
        'barang_total',
        'barang_ket',
        'barang_tgl_beli',
    ];
}

