<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingWaktuTagihan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'wt_jeda_isolir_hari',
        'wt_jeda_tagihan_pertama',
    ];
}
