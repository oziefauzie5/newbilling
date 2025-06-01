<?php

namespace App\Models\PSB;

use App\Models\Mitra\MitraSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class InputData extends Model implements Authenticatable
{
    use AuthenticableTrait;
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'data__site_id',
        'input_tgl',
        'input_nama',
        'input_ktp',
        'input_hp',
        'input_hp_2',
        'input_email',
        'input_alamat_ktp',
        'input_alamat_pasang',
        'input_sales',
        'input_subseles',
        'password',
        'input_maps',
        'input_koordinat',
        'input_status',
        'input_keterangan',
        'idpel_sementara',
    ];

    function user_sales()
    {
        return $this->hasOne(User::class,'id','input_sales');
    }
     function input_mitra()
    {
        return $this->hasOne(MitraSetting::class,'mts_user_id','input_sales');
    }
    
}
