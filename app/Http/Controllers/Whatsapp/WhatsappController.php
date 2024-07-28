<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Pesan\Pesan;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function index()
    {
        $data['whatsapp'] = Pesan::paginate(15);
        // dd($data);
        return view('whatsapp/index', $data);
    }
}
