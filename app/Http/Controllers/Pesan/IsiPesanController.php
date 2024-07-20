<?php

namespace App\Http\Controllers\Pesan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IsiPesanController extends Controller
{
    public function tiket_team()
    {
        $pesan['status'] = 'tiket';
        // $pesan['hp'] = $tampildata->hp;

        $pesan['pesan'] = 'Hallo Broo..  
        Ada tiket masuk ke sistem nih! 😊
        Notiket : T487484
        Topik : Nama Internet Tidak Muncul
        Deskripsi : Nama Internet Tidak Muncul
        Pelanggan : IRVAN MAULANA
        Alamat : KEDUNG HALANG SERIKAT RT O1/01 (DEKAT RIDHO CELL)
        Tanggal tiket : 20 Juli 2024 07:47:58
        Mohon segera diproses dari aplikasi dan di tindak lanjuti ya.
        Terima kasih.';
    }
}
