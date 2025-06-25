<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aplikasi\Data_Site;

class gudang extends Model
{
    use HasFactory;
       protected $fillable = [
           'corporate_id',
           'gudang_alamat',
           'gudang_nama',
           'gudang_status',
           'data__site_id',
    ];
    // function gudang_site()
    // {
    //     return $this->HasMany(Data_Site::class,'id','data__site_id');
    // }
}
