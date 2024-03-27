<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{

    public function index()
    {

    }
    public function create(Request $request)
    {

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['id_kategori']=$request->id_kategori;
        $data['nama_kategori']=$request->nama_kategori;
       $cek = Kategori::where('nama_kategori',$request->nama_kategori)->first();
    //    dd($cek);
       if($cek==null){
           Kategori::create($data);
           $notifikasi = array(
               'pesan' => 'Berhasil menambahkan Kategori',
               'alert' => 'success',
           );
           return redirect()->route('admin.barang.index')->with($notifikasi);
       } else {
        $notifikasi = array(
            'pesan' => 'Gagal menambahkan Kategori. Kategori '.$request->nama_kategori.' sudah terdaftar. ',
            'alert' => 'error',
        );
        return redirect()->route('admin.barang.index')->with( $notifikasi);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
