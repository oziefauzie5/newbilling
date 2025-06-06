<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'mt_mts_id',
        'mt_admin',
        'mt_kategori',
        'mt_deskripsi',
        'mt_cabar',
        'mt_kredit',
        'mt_debet',
        'mt_saldo',
        'mt_biaya_adm',
        'mt_status',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;
}
