<?php

namespace App\Models\Registrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Deaktivasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'deaktivasi_idpel',
        'deaktivasi_mac',
        'deaktivasi_sn',
        'deaktivasi_kelengkapan_perangkat',
        'deaktivasi_tanggal_pengambilan',
        'deaktivasi_pengambil_perangkat',
        'deaktivasi_admin',
        'deaktivasi_alasan_deaktivasi',
        'deaktivasi_pernyataan',

    ];
}
