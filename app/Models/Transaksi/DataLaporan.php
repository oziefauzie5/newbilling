<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLaporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'data_lap_id',
        'data_lap_tgl',
        'data_lap_pendapatan',
        'data_lap_admin',
        'data_lap_trx',
    ];
}
