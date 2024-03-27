<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputData extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'input_tgl',
        'input_nama',
        'input_ktp',
        'input_hp',
        'input_email',
        'input_alamat_ktp',
        'input_alamat_pasang',
        'input_sales',
        'input_subseles',
        'input_password',
        'input_maps',
        'input_kordinat',
        'input_status',
        'input_keterangan',
    ];
}
