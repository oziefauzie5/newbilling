<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|NOC|cs|TEKNISI|SALES|STAF ADMIN|KOLEKTOR']);
    }
    public function home()
    {
        $data = array(
            'tittle' => 'Dashboard',
        );
        return view('home/index', $data);
    }
}
