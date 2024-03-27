<?php

namespace App\Models\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_supplier',
        'supplier_nama',
        'supplier_alamat',
        'supplier_tlp',

    ];
}


