<?php

namespace App\Models\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_subbarang',
        'subbarang_idbarang',
        'subbarang_nama',
        'subbarang_ktg',
        'subbarang_qty',
        'subbarang_keluar',
        'subbarang_stok',
        'subbarang_harga',
        'subbarang_keterangan',
        'subbarang_sn',
        'subbarang_mac',
        'subbarang_status',
        'subbarang_tgl_masuk',
    ];
}
