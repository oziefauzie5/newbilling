<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'jurnal_id',
        'jurnal_tgl',
        'jurnal_uraian',
        'jurnal_qty',
        'jurnal_keterangan',
        'jurnal_kategori',
        'jurnal_admin',
        'jurnal_penerima',
        'jurnal_idpel',
        'jurnal_metode_bayar',
        'jurnal_debet',
        'jurnal_kredit',
        'jurnal_saldo',
        'jurnal_img',
        'jurnal_status',
    ];
}
