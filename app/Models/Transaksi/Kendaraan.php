<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'trans_user_id',
        'trans_divisi_id',
        'trans_plat_nomor',
        'trans_jenis_motor',
        'trans_bensin',
        'trans_service',
        'trans_sewa',
        'trans_status',
    ];
}
