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
            'barang_id',
            'corporate_id',
            'barang_id_group',
            'barang_lokasi',
            'barang_kategori',
            'barang_jenis',
            'barang_jenis_jurnal',
            'barang_nama',
            'barang_merek',
            'barang_qty',
            'barang_digunakan',
            'barang_dijual',
            'barang_dicek',
            'barang_rusak',
            'barang_pengembalian',
            'barang_hilang',
            'barang_satuan',
            'barang_sn',
            'barang_mac',
            'barang_mac_olt',
            'barang_tglmasuk',
            'barang_harga',
            'barang_harga_satuan',
            'barang_status',
            'barang_img',
            'barang_ket',
            'barang_nama_pengguna',
            'barang_admin_update',
            'barang_penerima',
            'barang_pengecek',
            'barang_status_print',
            'barang_hilang',
            'created_at',
        ]);
    }
}
