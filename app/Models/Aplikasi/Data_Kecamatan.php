<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Kecamatan extends Model
{
    use HasFactory;
      protected $fillable = [
        'corporate_id',
        'data__site_id',
        'kec_nama',
    ];
     function kecamatan_site()
    {
        return $this->hasOne(Data_Site::class,'id','data__site_id');
    }
}
