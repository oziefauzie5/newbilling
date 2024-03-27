<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingBiaya extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'biaya_pasang',
        'biaya_ppn',
        'biaya_psb',
        'biaya_sales',
        'biaya_deposit',
        'biaya_kas',
        'biaya_kerjasama',
    ];
}
