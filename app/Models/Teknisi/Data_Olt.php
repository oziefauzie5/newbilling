<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Olt extends Model
{
    use HasFactory;
    protected $fillable = [
        'olt_id',
        'olt_id_pop',
        'olt_kode',
        'olt_nama',
        'olt_merek',
        'olt_mac',
        'olt_sn',
        'olt_pon',
        'olt_ip',
        'olt_username',
        'olt_password',
        'olt_ip_default',
        'olt_username_default',
        'olt_password_default',
        'olt_topologi_img',
        'olt_keterangan',
        'olt_status',
    ];
}
