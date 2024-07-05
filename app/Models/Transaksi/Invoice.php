<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'inv_id',
        'inv_status',
        'inv_idpel',
        'inv_nolayanan',
        'inv_nama',
        'inv_jenis_tagihan',
        'inv_profile',
        'inv_mitra',
        'inv_kategori',
        'inv_tgl_pasang',
        'inv_tgl_tagih',
        'inv_tgl_jatuh_tempo',
        'inv_tgl_isolir',
        'inv_tgl_bayar',
        'inv_periode',
        'inv_diskon',
        'inv_total',
        'inv_cabar',
        'inv_akun',
        'inv_reference',
        'inv_payment_method',
        'inv_payment_method_code',
        'inv_total_amount',
        'inv_fee_merchant',
        'inv_fee_customer',
        'inv_total_fee',
        'inv_amount_received',
        'inv_admin',
        'inv_note',
    ];
}
