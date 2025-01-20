<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_BarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'bk_id',
        'bk_jenis_laporan',
        'bk_id_tiket',
        'bk_id_barang',
        'bk_kategori',
        'bk_satuan',
        'bk_harga',
        'bk_nama_barang',
        'bk_model',
        'bk_mac',
        'bk_sn',
        'bk_jumlah',
        'bk_keperluan',
        'bk_foto_awal',
        'bk_foto_akhir',
        'bk_nama_penggunan',
        'bk_waktu_keluar',
        'bk_admin_input',
        'bk_penerima',
        'bk_status',
        'bk_keterangan',
    ];
}
