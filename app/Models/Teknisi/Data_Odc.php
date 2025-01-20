<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Odc extends Model
{
    use HasFactory;
    protected $fillable = [
        'odc_id',
        'odc_kode',
        'odc_id_olt',
        'odc_pon_olt',
        'odc_core',
        'odc_nama',
        'odc_jumlah_port',
        'odc_topologi_img',
        'odc_lokasi_img',
        'odc_koordinat',
        'odc_keterangan',
        'odc_status',
    ];
}
