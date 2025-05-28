<?php

namespace App\Models\Teknisi;

use App\Models\Aplikasi\Data_Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_pop extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'data__site_id',
        'pop_nama',
        'pop_alamat',
        'pop_koordinat',
        'pop_file_topologi',
        'pop_status',
    ];

    function pop_site()
    {
        return $this->hasOne(Data_Site::class,'id','data__site_id');
    }
}
