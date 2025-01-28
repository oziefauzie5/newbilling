<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'barang_id',
        'barang_id_group',
        'barang_lokasi',
        'barang_kategori',
        'barang_jenis',
        'barang_nama',
        'barang_merek',
        'barang_qty',
        'barang_digunakan',
        'barang_dijual',
        'barang_rusak',
        'barang_pengembalian',
        'barang_satuan',
        'barang_sn',
        'barang_mac',
        'barang_mac_olt',
        'barang_tglmasuk',
        'barang_harga',
        'barang_harga_satuan',
        'barang_status',
        'barang_img',
        'barang_ket',
        'barang_nama_pengguna',
        'barang_admin_update',
        'barang_penerima',
        'barang_pengecek',
        'created_at',
    ];
}
