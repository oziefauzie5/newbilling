<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_id',
        'site_prefix',
        'site_nama',
        'site_brand',
        'site_keterangan',
        'site_status',
    ];
}
