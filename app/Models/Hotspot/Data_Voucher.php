<?php

namespace App\Models\Hotspot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
            'vhc_id',
            'vhc_pesananid',
            'vhc_username',
            'vhc_password',
            'vhc_paket',
            'vhc_site',
            'vhc_router',
            'vhc_mitra',
            'vhc_outlet',
            'vhc_hpp',
            'vhc_komisi',
            'vhc_hjk',
            'vhc_tgl_cetak',
            'vhc_tgl_jual',
            'vhc_tgl_hapus',
            'vhc_exp',
            'vhc_durasi_pakai',
            'vhc_kuota',
            'vhc_admin',
            'vhc_status',
            'vhc_status_pakai',
            'vhc_mac',
            'vhc_script',
    ];

            

}
