<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapMingguan extends Model
{
    use HasFactory;
    protected $fillable = [
        'lm_id',
        'lm_admin',
        'lm_debet',
        'lm_kredit',
        'lm_saldo_akhir',
        'lm_adm',
        'lm_akun',
        'lm_keterangan',
        'lm_periode',
        'lm_status',
        'lm_img',
    ];
}
