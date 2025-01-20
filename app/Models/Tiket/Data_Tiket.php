<?php

namespace App\Models\Tiket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Tiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'tiket_id',
        'tiket_pending',
        'tiket_pembuat',
        'tiket_kode',
        'tiket_idpel',
        'tiket_jenis',
        'tiket_nama',
        'tiket_waktu_kunjungan',
        'tiket_waktu_penanganan',
        'tiket_waktu_selesai',
        'tiket_foto',
        'tiket_kendala',
        'tiket_tindakan',
        'tiket_keterangan',
        'tiket_teknisi1',
        'tiket_teknisi2',
        'tiket_barang',
        'tiket_status',
    ];
}
