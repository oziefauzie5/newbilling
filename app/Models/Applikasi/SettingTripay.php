<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTripay extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'tripay_merchant',
        'tripay_url_callback',
        'tripay_kode_merchant',
        'tripay_apikey',
        'tripay_privatekey',
        'tripay_admin_topup',
        'tripay_status',
    ];
}
