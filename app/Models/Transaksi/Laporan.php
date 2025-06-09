<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'lap_id',
        'data_lap_id',
        'corporate_id',
        'lap_inv',
        'lap_tgl',
        'lap_fee_mitra',
        'lap_ppn',
        'lap_bph_uso',
        'lap_admin',
        'lap_pokok',
        'lap_jumlah',
        'lap_akun',
        'lap_keterangan',
        'lap_jenis_inv',
        'lap_status',
        'lap_img',
         'created_at',
        'updated_at',
    ];
    public $timestamps = false;
}
