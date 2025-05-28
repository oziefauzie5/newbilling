<?php

namespace App\Models\Hotspot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titikvhc extends Model
{
    use HasFactory;
    protected $fillable = [
        'titik_id', #
        'corporate_id', #
        'titik_nama', #
        'titik_nama_titik', #
        'titik_pen_jawab_id',
        'titik_pen_jawab_hp', #
        'titik_alamat', #
        'titik_maps', #
        'titik_ip', #
        'titik_penangguang_jawab',
        'titik_username', #
        'titik_password', #
        'titik_wilayah',
        'titik_mac', #
        'titik_sn', #
        'titik_mrek', #
        'titik_tgl_pasang',
        'titik_teknisi_team',
        'titik_kode_pactcore',
        'titik_kode_adaptor',
        'titik_kode_dropcore',
        'titik_kode_ont',
        'titik_fat',
        'titik_fat_opm',
        'titik_home_opm',
        'titik_los_opm',
        'titik_router', #
        'titik_before',
        'titik_after',
        'titik_penggunaan_dropcore',
        'titik_catatan',
        'titik_progres',
        'titik_stt_perangkat', #
        'titik_slotonu',
        'titik_img',
    ];
}
