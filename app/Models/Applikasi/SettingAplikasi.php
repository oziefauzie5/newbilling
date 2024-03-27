<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingAplikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'app_id',
        'app_nama',
        'app_brand',
        'app_alamat',
        'app_npwp',
        'app_logo',
        'app_favicon',
        'app_link_apk',
    ];
}
