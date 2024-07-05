<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'lap_id',
        'lap_tgl',
        'lap_inv',
        'lap_admin',
        'lap_cabar',
        'lap_debet',
        'lap_kredit',
        'lap_ppn',
        'lap_jumlah_bayar',
        'lap_akun',
        'lap_keterangan',
        'lap_jenis_inv',
        'lap_status',
        'lap_img',
        'lap_idpel',
    ];
}
