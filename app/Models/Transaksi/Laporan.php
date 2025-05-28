<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'lap_id',
        'lap_tgl',
        'lap_inv',
        'lap_admin',
        'lap_cabar',
        'lap_debet',
        'lap_kredit',
        'lap_fee_lingkungan',
        'lap_fee_kerja_sama',
        'lap_fee_marketing',
        'lap_ppn',
        'lap_adm',
        'lap_jumlah_bayar',
        'lap_akun',
        'lap_keterangan',
        'lap_jenis_inv',
        'lap_status',
        'lap_img',
        'lap_idpel',
    ];
}
