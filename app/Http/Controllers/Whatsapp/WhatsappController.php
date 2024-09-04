<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Global\GlobalController;
use App\Models\Pesan\Pesan;
use App\Models\Router\Router;
use App\Models\PSB\Registrasi;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function index(Request $request)
    {
        $data['q'] = $request->query('q');
        $query = Pesan::orderBy('created_at', 'DESC')
            ->where(function ($query) use ($data) {
                $query->where('nama', 'like', '%' . $data['q'] . '%');
                $query->orWhere('target', 'like', '%' . $data['q'] . '%');
                $query->orWhere('pesan', 'like', '%' . $data['q'] . '%');
                $query->orWhere('status', 'like', '%' . $data['q'] . '%');
                $query->orWhere('ket', 'like', '%' . $data['q'] . '%');
            });

        $data['whatsapp'] = $query->paginate(15);

        $data['router'] = Router::get();
        // dd($data['router']);

        return view('whatsapp/index', $data);
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
