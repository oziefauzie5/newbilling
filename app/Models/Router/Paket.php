<?php

namespace App\Models\Router;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;
    protected $fillable = [
        'paket_id',
        'paket_nama',
        'paket_limitasi',
        'paket_shared',
        'paket_masa_aktif',
        'paket_komisi',
        'paket_harga',
        'paket_lokal',
        'paket_remote_address',
        'paket_qris',
        'paket_status',
        'paket_layanan',
        'paket_mode',
        'paket_warna',

    ];
}
