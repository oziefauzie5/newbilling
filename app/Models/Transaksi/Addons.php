<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addons extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'addons_nama',
        'addons_stok',
        'addons_harga',
        'addons_idpel',
    ];
}
