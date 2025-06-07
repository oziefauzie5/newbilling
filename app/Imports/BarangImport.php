<?php

namespace App\Imports;

use App\Models\Gudang\Data_Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangImport implements ToModel

{
    /**
    * @param Collection $collection
    */
   public function model(array $row)
    {
        return new Data_Barang([
            'corporate_id' =>Session::get('corp_id'),
            'barang_id' =>$row[0],
            'barang_id_group' =>$row[1],
            'barang_lokasi' =>$row[2],
            'barang_kategori' =>$row[3],
            'barang_jenis' =>$row[4],
            'barang_jenis_jurnal' =>$row[5],
            'barang_nama' =>$row[6],
            'barang_merek' =>$row[7],
            'barang_qty' =>$row[8],
            'barang_dicek' =>$row[9],
            'barang_digunakan' =>$row[10],
            'barang_dijual' =>$row[11],
            'barang_rusak' =>$row[12],
            'barang_pengembalian' =>$row[13],
            'barang_hilang' =>$row[14],
            'barang_satuan' =>$row[15],
            'barang_sn' =>$row[16],
            'barang_mac' =>$row[17],
            'barang_mac_olt' =>$row[18],
            'barang_tglmasuk' =>$row[19],
            'barang_harga' =>$row[20],
            'barang_harga_satuan' =>$row[21],
            'barang_status' =>$row[22],
            'barang_img' =>$row[23],
            'barang_ket' =>$row[24],
            'barang_nama_pengguna' =>$row[25],
            'barang_admin_update' =>$row[26],
            'barang_penerima' =>$row[27],
            'barang_pengecek' =>$row[28],
            'barang_status_print' =>$row[29],
            'created_at' =>$row[30],
            'updated_at' =>$row[31],
        ]);
    }
}
