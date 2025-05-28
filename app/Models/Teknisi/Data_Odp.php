<?php

namespace App\Models\Teknisi;

use App\Models\PSB\FtthInstalasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Odp extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'data__odc_id',
        'odp_id',
        'odp_core',
        'odp_nama',
        'odp_jumlah_slot',
        'odp_lokasi_img',
        'odp_file_topologi',
        'odp_koordinat',
        'odp_keterangan',
        'odp_status',
        'odp_slot_odc',
    ];

       function data_isntalasi()
    {
        return $this->hasMany(FtthInstalasi::class,'data__odp_id','id');
    }
}
