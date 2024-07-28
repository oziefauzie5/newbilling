<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $data['bulan'] = strtoupper(date('F', strtotime(Carbon::now())));
        $whereMonth = date('m', strtotime(Carbon::now()));
        $query = Transaksi::whereMonth('created_at', $whereMonth);
        $data['transaksi'] = $query->get();
        $data['sum_pemasukan'] = $query->where('trx_kategori', 'PEMASUKAN')->sum('trx_total');
        $data['sum_pengeluaran'] = $query->where('trx_kategori', 'PENGELUARAN')->sum('trx_total');
        return view('Transaksi/transaksi', $data);
    }
}
