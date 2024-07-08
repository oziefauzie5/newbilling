<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'reg_idpel',
        'reg_clientid',
        'reg_nolayanan',
        'reg_layanan',
        'reg_profile',
        'reg_jenis_tagihan',
        'reg_harga',
        'reg_kode_unik',
        'reg_deposit',
        'reg_ppn',
        'reg_dana_kas',
        'reg_dana_kerjasama',
        'reg_username',
        'reg_password',
        'reg_tgl_pasang',
        'reg_tgl_jatuh_tempo',
        'reg_tgl_tagih',
        'reg_tgl_isolir',
        'reg_wilayah',
        'reg_fat',
        'reg_fat_opm',
        'reg_home_opm',
        'reg_los_opm',
        'reg_router',
        'reg_mrek',
        'reg_mac',
        'reg_sn',
        'reg_kode_pactcore',
        'reg_kode_ont',
        'reg_kode_adaptor',
        'reg_kode_dropcore',
        'reg_before',
        'reg_after',
        'reg_penggunaan_dropcore',
        'reg_ip_address',
        'reg_catatan',
        'reg_status',
        'reg_progres',
        'reg_stt_perangkat',
        'reg_slotonu',
        'reg_teknisi_team',
        'reg_inv_control',
    ];
}
