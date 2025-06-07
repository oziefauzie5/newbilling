<?php

namespace App\Models\PSB;

use App\Models\Router\Paket;
use App\Models\Tiket\Data_Tiket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'reg_idpel',
        'corporate_id',
        'reg_profile',
        'reg_nolayanan',
        'reg_layanan',
        'reg_jenis_tagihan',
        'reg_harga',
        'reg_ppn',
        'reg_bph_uso',
        'reg_kode_unik',
        'reg_inv_control',
        'reg_tgl_pasang', #aKTIVASI
        'reg_tgl_jatuh_tempo', #aKTIVASI
        'reg_tgl_tagih', #aKTIVASI
        'reg_tgl_deaktivasi', #Berlangganan
        'reg_img',
        'reg_catatan',
        'reg_status',
        'reg_progres',
        'reg_username',
        'reg_password',
        'created_at',
        'updated_at',
    ];

    // public $timestamps = false;
    function registrasi_router()
    {
        return $this->hasMany(Registrasi::class,'id','reg_router');
    }

       function registrasi_paket()
    {
        return $this->hasOne(Paket::class,'paket_id','reg_profile');
    }
       function registrasi_tiket()
    {
        return $this->hasOne(Data_Tiket::class,'tiket_idpel','reg_idpel');
    }

  
}
