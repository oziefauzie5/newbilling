<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Pesan\Pesan;
use App\Models\PSB\InputData;
use App\Models\Router\Router;
use App\Models\PSB\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class WhatsappController extends Controller
{
    public function index(Request $request)
    {
        $data['q'] = $request->query('q');
        $data['ket'] = $request->query('ket');
        $data['status'] = $request->query('status');
        $query = Pesan::orderBy('created_at', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('target', 'like', '%' . $data['q'] . '%');
                $query->orWhere('pesan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('status', 'like', '%' . $data['q'] . '%');
                $query->orWhere('ket', 'like', '%' . $data['q'] . '%');
            });
            if($data['status'])
            $query->where('status','!=','Done');

        if($data['ket'] == 'payment')
            $query->where('ket','=','payment');
        elseif($data['ket'] == 'tiket')
            $query->where('ket','=','tiket');
        elseif($data['ket'] == 'tagihan')
            $query->where('ket','=','tagihan');

        

        $data['whatsapp'] = $query->paginate(15);
        $data['belum_terkirim'] = Pesan::where('status', '0')->count();
        $data['terkirim'] = Pesan::where('status', 'Done')->count();
        $data['gagalbo'] = Pesan::where('status', 'Gagal')->count();
        $data['router'] = Router::get();
        // dd($data['router']);

        return view('whatsapp/index', $data);
    }

    public function kirim_pesan_manual($id)
    {
        Pesan::whereId($id)->update([
            'status' => 'Done',
        ]);
        return response()->json('berhasil');
    }
    public function delete_pesan($id)
    {
        // dd($id);
        $pesan = Pesan::whereId($id)->first();
        if ($pesan) {
            $pesan->delete();
        }
        $notifikasi = [
            'pesan' => 'Berhasil Hapus Pesan',
            'alert' => 'success',
        ];
        return redirect()->route('admin.wa.index')->with($notifikasi);
    }

    public function broadcast(Request $request)
    {
        $data_pelanggan = Registrasi::join('input_data', 'input_data.id', '=', 'registrasis.reg_idpel')->where('reg_router', $request->router)->get();
        $status = (new GlobalController)->whatsapp_status();
        foreach ($data_pelanggan as $d) {
            if ($status->wa_status == 'Enable') {

                Pesan::create([
                    'ket' => 'tagihan',
                    'status' => '0',
                    'target' => $d->input_hp,
                    'nama' => $d->input_nama,
                    'pesan' => '
' . $request->pesan . '
',
                ]);
            } else {

                Pesan::create([
                    'ket' => 'tagihan',
                    'status' => '10',
                    'target' => $d->input_hp,
                    'nama' => $d->input_nama,
                    'pesan' => '
' . $request->pesan . '
',
                ]);
                $pesan_group['status'] = '10';
            }
        }
        $notifikasi = [
            'pesan' => 'Berhasil Kirim Pesan Broadcast',
            'alert' => 'success',
        ];
        return redirect()->route('admin.wa.index')->with($notifikasi);
    }
}
