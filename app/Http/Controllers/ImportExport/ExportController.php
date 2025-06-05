<?php

namespace App\Http\Controllers\ImportExport;

use App\Exports\InputDataExport;
use App\Exports\RegistExport;
use App\Http\Controllers\Controller;
use App\Imports\FtthFeeImport;
use Illuminate\Http\Request;
use App\Imports\InputDataImport;
use App\Imports\OdcImport;
use App\Imports\OdpImport;
use App\Imports\OltImport;
use App\Imports\PaketImport;
use App\Imports\PopImport;
use App\Imports\RegistImport;
use App\Models\PSB\FtthInstalasi;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PSB\InputData;


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
        // $data_excel = (new InpuDataExport());
        $data_excel = (new FtthInstalasi());
        return Excel::download($data_excel, 'registrasi.xlsx');
    }
      public function export_ftth_fees(Request $request)
    {
        // $data_excel = (new InpuDataExport());
        $data_excel = (new FtthFeeImport());
        return Excel::download($data_excel, 'registrasi.xlsx');
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
}
