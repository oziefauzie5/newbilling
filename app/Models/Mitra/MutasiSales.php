<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'smt_user_id',
        'smt_admin',
        'smt_idpel',
        'smt_tgl_transaksi',
        'smt_kategori',
        'smt_deskripsi',
        'smt_cabar',
        'smt_kredit',
        'smt_debet',
        'smt_saldo',
        'smt_biaya_adm',
        'smt_status',
    ];
}
