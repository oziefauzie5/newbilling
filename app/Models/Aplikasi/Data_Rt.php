<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Rt extends Model
{
    use HasFactory;
       protected $fillable = [
        'rt_id',
        'corporate_id',
        'rt_kel_id',
        'rt_nama',
        'rt_ket',
        'rt_status',
    ];
}
