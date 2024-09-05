<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasFactory;
    protected $fillable = [
        'kasbon_user_id',
        'kasbon_jenis',
        'kasbon_tempo',
        'kasbon_uraian',
        'kasbon_kredit',
        'kasbon_debet',
        'kasbon_file',
        'kasbon_status',
    ];
}
