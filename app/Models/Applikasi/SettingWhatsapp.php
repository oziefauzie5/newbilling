<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingWhatsapp extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'wa_nama',
        'wa_key',
        'wa_url',
        'wa_nomor',
        'wa_status',
    ];
}
