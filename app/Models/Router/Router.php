<?php

namespace App\Models\Router;

use App\Models\Aplikasi\Data_Site;
use App\Models\Teknisi\Data_pop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'data_pop_id',
        'router_nama',
        'router_ip',
        'router_dns',
        'router_port_api',
        'router_port_remote',
        'router_username',
        'router_password',
        'router_status',
    ];

    function pop_router()
    {
        return $this->hasOne(Data_pop::class,'id','data_pop_id');
    }
    
   
}
