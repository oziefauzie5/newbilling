<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang extends Model
{
    use HasFactory;
       protected $fillable = [
           'corporate_id',
           'gudang_alamat',
           'data__site_id',
    ];
}
