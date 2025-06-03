<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterSub extends Model
{
    use HasFactory;
    protected $fillable = [
        'router_sub_id',
        'router_id',
        'corporate_id'
    ];
    public $timestamps = true; 
   
}
