<?php

namespace App\Models\Mitra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'mitra_id',
        'client_id',
        'merchant',
        'type',
        'description',
        'fee_merchant',
    ];
}
