<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'inv_id',
        'inv_status',
        'inv_idpel',
        'inv_nolayanan',
        'inv_nama',
        'inv_jenis_tagihan',
        'inv_profile',
        'inv_mitra',
        'inv_kategori',
        'inv_tgl_tagih',
        'inv_tgl_jatuh_tempo',
        'inv_tgl_isolir',
        'inv_periode',
        'inv_diskon',
        'inv_total',
        'inv_note',
    ];
}
