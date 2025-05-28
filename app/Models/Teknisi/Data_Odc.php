<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Odc extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'odc_id',
        'data__olt_id',
        'odc_pon_olt',
        'odc_core',
        'odc_nama',
        'odc_jumlah_port',
        'odc_file_topologi',
        'odc_lokasi_img',
        'odc_koordinat',
        'odc_keterangan',
        'odc_status',
    ];

       function odp_odc()
    {
        return $this->hasMany(Data_Odp::class,'data__odc_id','id');
    }
}
