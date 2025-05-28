<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtthFee extends Model
{
    use HasFactory;
     protected $fillable = [
        'corporate_id',
        'fee_idpel',
        'reg_mitra',
        'reg_fee',
        ];
   
}
