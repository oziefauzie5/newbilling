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
        
        // 'reg_dana_kas',
        // 'reg_dana_kerjasama',
        // 'reg_fee',
        // 'reg_site',
        // 'reg_pop',
        // 'reg_router',
        // 'reg_in_ont',
        // 'reg_olt',
        // 'reg_odc',
        // 'reg_odp',
        // 'reg_slot_odp',
        // 'reg_onuid',
        // 'reg_koordinat_odp',
        // 'reg_foto_odp',

        // 'reg_sn',
        // 'reg_skb',
        // 'reg_ip_address',
        // 'reg_teknisi_team',
    ];

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
