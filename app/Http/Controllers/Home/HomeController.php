<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\PSB\InputData;
use App\Models\PSB\Registrasi;
use App\Models\Tiket\Data_Tiket;
use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|NOC|STAF ADMIN']);
    }
    public function home()
    {
        $date = date('Y-m-d', strtotime(carbon::now()));
        $data['count_inputdata'] = InputData::where('input_status', '!=', 'REGIST')->count();
        $data['count_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_berlangganan_ppp'] = Registrasi::where('reg_layanan', '=', 'PPP')->where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_berlangganan_hotspot'] = Registrasi::where('reg_layanan', '=', 'HOTSPOT')->where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '!=', 'FREE')->count();
        $data['count_free_berlangganan'] = Registrasi::where('reg_progres', '>=', '3')->where('reg_jenis_tagihan', '=', 'FREE')->count();
        $data['count_ps'] = Registrasi::where('reg_progres', '90')->count();
        $data['count_pb'] = Registrasi::where('reg_progres', '100')->count();

        $query_tiket = Data_Tiket::where('tiket_status', 'NEW')->where('tiket_type', 'General');
        $data['count_tiket_general'] = $query_tiket->count();
        $data['count_tiket_general_hari_ini'] = $query_tiket->whereDate('created_at', $date)->count();

        $query_tiket_project = Data_Tiket::where('tiket_status', 'NEW')->where('tiket_type', 'Project');
        $data['count_tiket_project'] = $query_tiket_project->count();
        $data['count_tiket_project_hari_ini'] = $query_tiket_project->whereDate('created_at', $date)->count();

        $query_tiket_closed = Data_Tiket::where('tiket_status', 'Closed')->where('tiket_type', 'General');
        $data['count_tiket_closed'] = $query_tiket_closed->count();
        $data['count_tiket_closed_hari_ini'] = $query_tiket_closed->whereDate('created_at', $date)->count();
        $query_tiket_pending = Data_Tiket::where('tiket_status', 'Closed')->where('tiket_type', 'General');
        $data['count_tiket_pending'] = $query_tiket_pending->count();
        $data['count_tiket_pending_hari_ini'] = $query_tiket_pending->whereDate('created_at', $date)->count();

        $data['count_ppp'] = Registrasi::where('reg_layanan', 'PPP')->count();
        $data['count_total_inv'] = Invoice::where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();
        $data['count_tiket'] = Invoice::where('inv_status', '!=', 'PAID')->whereMonth('inv_tgl_jatuh_tempo', '!=', 'PAID')->count();
        return view('home/index', $data);
    }
}
