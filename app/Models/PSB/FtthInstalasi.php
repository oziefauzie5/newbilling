<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtthInstalasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'reg_barang_id',
        'corporate_id',
        'data__odp_id',
        'reg_noc',
        'instalasi_idpel',
        'reg_in_ont',
        'reg_slot_odp',
    ];

    
}
