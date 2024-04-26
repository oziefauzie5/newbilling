<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_unpaid',
        'idpel_unpaid',
        'reference',
        'payment_method',
        'payment_method_code',
        'total_amount',
        'fee_merchant',
        'fee_customer',
        'total_fee',
        'amount_received',
        'is_closed_payment',
        'status',
        'paid_at',
        'admin',
        'akun',
        'note',
    ];
}
