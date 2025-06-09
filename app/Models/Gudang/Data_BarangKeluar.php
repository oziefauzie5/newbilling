<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_BarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'bk_id',
        'bk_id_barang',
        'corporate_id',
        'bk_idpel',
        'bk_jenis_laporan',
        'bk_kategori',
        'bk_before',
        'bk_after',
        'bk_terpakai',
        'bk_harga',
        'bk_jumlah',
        'bk_keperluan',
        'bk_file_bukti',
        'bk_nama_pengguna',
        'bk_waktu_keluar',
        'bk_admin_input',
        'bk_penerima',
        'bk_status',
        'bk_keterangan',
        'created_at',
        'updated_at',
    ];
    // public $timestamps = false;
}
