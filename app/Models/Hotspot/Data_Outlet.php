<?php

namespace App\Models\Hotspot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Outlet extends Model
{
    use HasFactory;
    protected $fillable = [
        'outlet_id',
        'outlet_nama',
        'outlet_pemilik',
        'outlet_hp',
        'outlet_mitra',
        'outlet_alamat',
        'outlet_tgl_gabung',
        'outlet_status',
];
}
