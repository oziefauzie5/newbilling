<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Pesan\Pesan;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function index()
    {
        $data['whatsapp'] = Pesan::get();
        // dd($data);
        return view('whatsapp/index', $data);
    }
}
