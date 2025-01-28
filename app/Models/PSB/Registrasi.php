<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'reg_idpel',
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
        'reg_fee',
        'reg_username',
        'reg_password',
        'reg_tgl_pasang',
        'reg_tgl_jatuh_tempo',
        'reg_tgl_tagih',
        'reg_tgl_deaktivasi',
        'reg_site',
        'reg_pop',
        'reg_router',
        'reg_in_ont',
        'reg_nama_barang',
        'reg_olt',
        'reg_odc',
        'reg_odp',
        'reg_slot_odp',
        'reg_mac_olt',
        'reg_onuid',
        'reg_mrek',
        'reg_mac',
        'reg_sn',
        'reg_skb',
        'reg_ip_address',
        'reg_catatan',
        'reg_status',
        'reg_progres',
        'reg_teknisi_team',
        'reg_inv_control',
        'reg_img',
    ];
}
