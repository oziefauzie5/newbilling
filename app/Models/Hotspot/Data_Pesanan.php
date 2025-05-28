<?php

namespace App\Models\Hotspot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Pesanan extends Model
{
    use HasFactory;
    protected $fillable = [
    'pesanan_id',
    'corporate_id',
    'pesanan_siteid',
    'pesanan_paketid',
    'pesanan_mitraid',
    'pesanan_outletid',
    'pesanan_routerid',
    'pesanan_jumlah',
    'pesanan_hpp',
    'pesanan_komisi',
    'pesanan_total_hpp',
    'pesanan_total_komisi',
    'pesanan_admin',
    'pesanan_tanggal',
    'pesanan_tgl_proses',
    'pesanan_tgl_bayar',
    'pesanan_status',
    'pesanan_status_generate',
    ];
}
