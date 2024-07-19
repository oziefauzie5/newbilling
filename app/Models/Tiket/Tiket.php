<?php

namespace App\Models\Tiket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'tiket_id',
        'tiket_status',
        'tiket_departemen',
        'tiket_nolayanan',
        'tiket_idpel',
        'tiket_pelanggan',
        'tiket_admin',
        'tiket_whatsapp',
        'tiket_judul',
        'tiket_prioritas',
        'tiket_deskripsi',
    ];
}
