<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'mts_id',
        'mts_user_id',
        'mts_limit_minus',
        'mts_kode_unik',
        'mts_komisi',
    ];
}
