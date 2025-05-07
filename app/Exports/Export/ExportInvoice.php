<?php

namespace App\Exports;

use App\Models\Transaksi\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportInvoice implements FromCollection, WithHeadings
{
    protected $data;
    function __construct($data)
    {
        // $this->data_inv = $data_inv;
        $this->data = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        return ['No. Invoice', 'No Layanan', 'Status', 'Nama', 'Jenis Tagihan', 'Paket', 'Tanggal Tagih', 'Jatuh Tempo', 'Tanggal Isolir',];
    }

    public function collection()
    {

        $data_inv = $this->data['data_inv'];
        $bulan = $this->data['bulan'];
        $query = Invoice::select('inv_id', 'inv_nolayanan', 'inv_status', 'inv_nama', 'inv_jenis_tagihan', 'inv_profile', 'inv_tgl_tagih', 'inv_tgl_jatuh_tempo', 'inv_tgl_isolir')
            ->orderBy('inv_tgl_jatuh_tempo', 'ASC');
        if ($data_inv)
            $query->where('inv_status', $data_inv);
        if ($bulan)
            $query->whereMonth('inv_tgl_jatuh_tempo', date('m', strtotime($bulan)))->whereYear('inv_tgl_jatuh_tempo', date('Y', strtotime($bulan)));

        return $query->get();
    }
}
