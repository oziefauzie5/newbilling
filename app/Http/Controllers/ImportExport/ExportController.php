<?php

namespace App\Http\Controllers\ImportExport;

use App\Exports\InputDataExport;
use App\Exports\RegistExport;
use App\Http\Controllers\Controller;
use App\Imports\AkunImport;
use App\Imports\BarangImport;
use App\Imports\BarangKeluarImport;
use App\Imports\FtthFeeImport;
use App\Imports\FtthInstalasiImport;
use Illuminate\Http\Request;
use App\Imports\InputDataImport;
use App\Imports\InvoiceImport;
use App\Imports\JurnalImport;
use App\Imports\KategoriImport;
use App\Imports\LaporanImport;
use App\Imports\MitraImport;
use App\Imports\MutasiImport;
use App\Imports\OdcImport;
use App\Imports\OdpImport;
use App\Imports\OltImport;
use App\Imports\PaketImport;
use App\Imports\PopImport;
use App\Imports\RegistImport;
use App\Imports\SubInvoiceImport;
use App\Imports\TeknisiImport;
use App\Imports\TiketImport;
use App\Imports\UserImport;
use App\Models\PSB\FtthFee;
use App\Models\PSB\FtthInstalasi;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PSB\InputData;
use App\Models\Tiket\Data_Tiket;

class ExportController extends Controller
{
    public function export_input_data(Request $request)
    {
        // $data_excel = (new InpuDataExport());
        $data_excel = (new InputDataExport());
        return Excel::download($data_excel, 'input_data.xlsx');
    }
    public function import_input_data(Request $request)
    {
        Excel::import(new InputDataImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }

      public function export_registrasi(Request $request)
    {
        // $data_excel = (new InpuDataExport());
        $data_excel = (new RegistExport());
        return Excel::download($data_excel, 'registrasi.xlsx');
    }
      public function import_instalasi(Request $request)
    {
        Excel::import(new FtthInstalasiImport(),request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.ftth')->with($notifikasi);
    }
      public function import_fee(Request $request)
    {
         Excel::import(new FtthFeeImport(),request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.ftth')->with($notifikasi);
    }
    public function import_registrasi(Request $request)
    {
        Excel::import(new RegistImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.ftth')->with($notifikasi);
    }
    public function import_pop(Request $request)
    {
        Excel::import(new PopImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.topo.pop')->with($notifikasi);
    }
    public function import_olt(Request $request)
    {
        Excel::import(new OltImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.topo.olt')->with($notifikasi);
    }
    public function import_odc(Request $request)
    {
        Excel::import(new OdcImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.topo.odc')->with($notifikasi);
    }
    public function import_odp(Request $request)
    {
        Excel::import(new OdpImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.topo.odp')->with($notifikasi);
    }
    public function import_paket(Request $request)
    {
        Excel::import(new PaketImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.noc.index')->with($notifikasi);
    }
    public function barang_import(Request $request)
    {
        Excel::import(new BarangImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('gudang.stok_gudang')->with($notifikasi);
    }
    public function barang_keluar_import(Request $request)
    {
        Excel::import(new BarangKeluarImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('gudang.stok_gudang')->with($notifikasi);
    }
    public function invoice_import(Request $request)
    {
        Excel::import(new InvoiceImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('inv.index')->with($notifikasi);
    }
    public function Subinvoice_import(Request $request)
    {
        Excel::import(new SubInvoiceImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('inv.index')->with($notifikasi);
    }
    public function import_laporan(Request $request)
    {
        Excel::import(new LaporanImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_teknisi(Request $request)
    {
        Excel::import(new TeknisiImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_mutasi(Request $request)
    {
        Excel::import(new MutasiImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_akun(Request $request)
    {
        Excel::import(new AkunImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_jurnal(Request $request)
    {
        Excel::import(new JurnalImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_user(Request $request)
    {
        Excel::import(new UserImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_kategori(Request $request)
    {
        Excel::import(new KategoriImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_mitra(Request $request)
    {
        Excel::import(new MitraImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
    public function import_tiket(Request $request)
    {
        Excel::import(new TiketImport(),request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.home')->with($notifikasi);
    }
   
}
