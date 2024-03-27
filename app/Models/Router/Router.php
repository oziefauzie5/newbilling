<?php

namespace App\Models\Router;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;
    protected $fillable = [
        'router_nama',
        'router_ip',
        'router_dns',
        'router_port_api',
        'router_port_remote',
        'router_username',
        'router_password',
        'router_status',
    ];
}
