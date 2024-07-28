<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Pesan\Pesan;
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

        return view('whatsapp/index', $data);
    }
}
