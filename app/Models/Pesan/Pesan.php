<?php

namespace App\Models\Pesan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;
    protected $fillable = [
        'target',
        'schedule',
        'delay',
        'pesan',
        'status',
        'ket',
    ];
}
