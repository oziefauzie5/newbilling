<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|noc|cs|Teknisi|Sales|Staf Admin']);
    }
    public function home()
    {
        $data = array(
            'tittle' => 'Dashboard',
        );
        return view('home/index', $data);
    }
}
