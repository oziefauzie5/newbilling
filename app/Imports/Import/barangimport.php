<?php

namespace App\Imports\Import;

use App\Models\Barang\SubBarang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class barangimport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd('cek');
        return new SubBarang([
            'id_subbarang ' => $row[0],
            'subbarang_idbarang' => $row[1],
            'subbarang_nama' => $row[2],
            'subbarang_ktg' => $row[3],
            'subbarang_qty' => $row[4],
            'subbarang_keluar' => $row[5],
            'subbarang_stok' => $row[6],
            'subbarang_harga' => $row[7],
            'subbarang_keterangan' => $row[8],
            'subbarang_sn' => $row[9],
            'subbarang_status' => $row[10],
            'subbarang_tgl_masuk' => $row[11],
            'subbarang_tgl_keluar' => $row[12],
            'subbarang_deskripsi' => $row[13],
            'subbarang_mac' =>  $row[14],
            'subbarang_admin' => $row[15],
        ]);
    }
}
