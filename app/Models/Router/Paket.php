<?php

namespace App\Models\Router;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;
    protected $fillable = [
        'paket_id',
        'corporate_id',
        // 'router_id',
        'paket_nama',
        'paket_limitasi',
        'paket_shared',
        'paket_masa_aktif',
        'paket_harga',
        'paket_komisi',
        'paket_lokal',
        'paket_remote_address',
        'paket_status',
        'paket_layanan',
        'paket_mode',
        'paket_warna',

    ];

       function paket_router()
    {
        return $this->hasOne(Router::class,'id','router_id');
    }
   
}
