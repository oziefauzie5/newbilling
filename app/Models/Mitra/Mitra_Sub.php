<?php

namespace App\Models\Mitra;

use App\Models\Aplikasi\Data_Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra_Sub extends Model
{
    use HasFactory;
     protected $fillable = [
        'mts_sub_user_id',
        'mts_sub_mitra_id',
        'corporate_id',
    ];

    function user_submitra()
    {
        return $this->hasOne(User::class,'id','mts_sub_user_id');
    }
    function submitra_site()
    {
        return $this->hasOne(Data_Site::class,'id','data__site_id');
    }
    function submitra_mitra() 
    #digunakan di RegistrasiController
    {
        return $this->hasMany(MitraSetting::class,'mts_user_id','mts_sub_mitra_id');
    }
     function mitra() 
    {
        return $this->hasOne(MitraSetting::class,'mts_user_id','mts_sub_mitra_id');
    }
    

}
