<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Lingkungan extends Model
{
    use HasFactory;
        protected $fillable = [
        'dl_id',
        'corporate_id',
        'dl_user_id',
        'dl_rt_id',
        'dl_kelurahan',
        'dl_rw',
        'dl_nama',
        'dl_fee_rw',
        'dl_jumlah_rt',
        'dl_fee_rt',
    ];
}
