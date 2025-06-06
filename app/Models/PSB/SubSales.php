<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'subsales_id',
        'subsales_nama',
        'subsales_fee',
        'subsales_status',
        'subsales_idpel',
        ];
}
