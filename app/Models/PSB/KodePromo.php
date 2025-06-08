<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodePromo extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'promo_id',
        'promo_nama',
        'promo_harga',
        'promo_expired',
    ];
}
