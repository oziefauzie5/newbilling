<?php

namespace App\Models\Teknisi;

use App\Models\Aplikasi\Data_Site;
use App\Models\Router\Router;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Olt extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'router_id',
        'olt_nama',
        'olt_pon',
        'olt_file_topologi',
        'olt_status',
    ];

       function olt_router()
    {
        return $this->hasOne(Router::class,'id','router_id');
    }
       function olt_odc()
    {
        return $this->hasMany(Data_Odc::class,'data__olt_id','id');
    }
     

    
}
