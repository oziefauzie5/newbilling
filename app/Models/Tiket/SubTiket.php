<?php

namespace App\Models\Tiket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTiket extends Model
{
    use HasFactory;
    protected $fillable = [
        'subtiket_id',
        'subtiket_admin',
        'subtiket_status',
        'subtiket_teknisi_team',
        'subtiket_deskripsi',
    ];
}
