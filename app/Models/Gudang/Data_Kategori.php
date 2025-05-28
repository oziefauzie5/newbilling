<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Kategori extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_kategori',
        'corporate_id',
        'nama_kategori',
        'jenis_jurnal_kategori',
        'kategori_satuan',
        'status_kategori',
    ];
}
