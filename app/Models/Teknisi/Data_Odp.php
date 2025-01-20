<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Odp extends Model
{
    use HasFactory;
    protected $fillable = [
        'odp_id',
        'odp_kode',
        'odp_id_odc',
        'odp_port_odc',
        'odp_opm_out_odc',
        'odp_opm_in',
        'odp_opm_out',
        'odp_core',
        'odp_nama',
        'odp_jumlah_port',
        'odp_lokasi_img',
        'odp_topologi_img',
        'odp_koordinat',
        'odp_keterangan',
        'odp_status',
    ];
}
