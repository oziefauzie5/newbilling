<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_pop extends Model
{
    use HasFactory;
    protected $fillable = [
        'pop_id',
        'pop_kode',
        'pop_id_site',
        'pop_nama',
        'pop_alamat',
        'pop_koordinat',
        'pop_ip1',
        'pop_ip2',
        'pop_topologi_img',
        'pop_keterangan',
        'pop_status',
    ];
}
