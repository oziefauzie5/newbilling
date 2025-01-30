<?php

namespace App\Models\Pesan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;
    protected $fillable = [
        'pesan_id_site',
        'layanan',
        'target',
        'nama',
        'schedule',
        'delay',
        'pesan',
        'status',
        'ket',
        'file',
    ];
}
