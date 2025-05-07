<?php

namespace App\Models\Hotspot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Bagihasil extends Model
{
    use HasFactory;
    protected $fillable = [
        'bh_id',
        'bh_mitraid',
        'bh_keterangan',
        'bh_saldo',
        'bh_persen',
        'bh_total_persentase',
        'bh_status',
        'bh_admin',
];
}
