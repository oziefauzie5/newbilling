<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corporate extends Model
{
    use HasFactory;
       protected $fillable = [
        'id',
        'corp_app_id',
        'corp_url',
    ];
}
