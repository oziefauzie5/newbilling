<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang\supplier;
use Illuminate\Http\Request;

class SupplierControoler extends Controller
{
    public function store(Request $request)
    {

        supplier::create([
            'id_supplier'=>$request->id_supplier,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat,
            'supplier_tlp'=>$request->supplier_tlp,
        ]);
        $notifikasi = array(
            'pesan' => 'Berhasil menambahkan Barang',
            'alert' => 'success',
        );
        return redirect()->route('admin.barang.index')->with($notifikasi);
    }

}
