<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingBiaya extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'biaya_pasang',
        'biaya_ppn',
        'biaya_psb',
        'biaya_sales',
        'biaya_bph_uso',
    ];
}
