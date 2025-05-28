<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'mts_mitra_id',
        'mts_mitra_idpel',
        'mts_admin',
        'mts_type',
        'mts_jumlah',
        'mts_deskripsi',
    ];
}
