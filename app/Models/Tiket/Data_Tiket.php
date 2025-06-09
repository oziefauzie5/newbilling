<?php

namespace App\Models\Tiket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Tiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
            'tiket_id',
            'tiket_idbarang_keluar',
            'tiket_pending',
            'tiket_pembuat',
            'tiket_idpel',
            'data__site_id',
            'tiket_jenis',
            'tiket_nama',
            'tiket_type',
            'tiket_jadwal_kunjungan',
            'tiket_waktu_mulai',
            'tiket_waktu_selesai',
            'tiket_foto',
            'tiket_kendala',
            'tiket_tindakan',
            'tiket_keterangan',
            'tiket_teknisi1',
            'tiket_teknisi2',
            'tiket_status',
            'created_at',
            'updated_at',
    ];
}
