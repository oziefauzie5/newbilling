<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'subinvoice_id',
        'subinvoice_deskripsi',
        'subinvoice_qty',
        'subinvoice_harga',
        'subinvoice_ppn',
        'subinvoice_total',
        'subinvoice_status'
    ];
}
