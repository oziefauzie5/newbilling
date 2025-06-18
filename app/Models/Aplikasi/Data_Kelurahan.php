<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Kelurahan extends Model
{
    use HasFactory;
     protected $fillable = [
        'corporate_id',
        'data__kecamatan_id',
        'kel_nama',
    ];
     function kecamatan()
    {
        return $this->hasOne(Data_Kecamatan::class,'id','data__kecamatan_id');
    }
}
