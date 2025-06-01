<?php

namespace App\Http\Controllers\ImportExport;

use App\Exports\InputDataExport;
use App\Exports\RegistExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\InputDataImport;
use App\Imports\RegistImport;
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
    public function import_registrasi(Request $request)
    {
        Excel::import(new RegistImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }
    public function import_pop(Request $request)
    {
        Excel::import(new RegistImport,request()->file('file'));
        $notifikasi = [
            'pesan' => 'Berhasil import Data',
            'alert' => 'success',
        ];
        return redirect()->route('admin.psb.list_input')->with($notifikasi);
    }
}
