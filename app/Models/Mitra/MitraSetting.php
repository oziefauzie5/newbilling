<?php

namespace App\Models\Mitra;

use App\Models\Aplikasi\Data_Site;
use App\Models\PSB\InputData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'mts_user_id',
        'corporate_id',
        'data__site_id',
        'mts_limit_minus',
        'mts_komisi',
        'mts_wilayah',
        'created_at',
            'updated_at',
    ];

    //  public $timestamps = false;
    function user_mitra()
    {
        return $this->hasOne(User::class,'id','mts_user_id');
    }
   
    function mitra_site()
    {
        return $this->hasOne(Data_Site::class,'id','data__site_id');
    }
    function mitra_sub()
    {
        return $this->hasMany(Mitra_Sub::class,'mts_sub_mitra_id','mts_user_id');
    }
    
   
    
}
