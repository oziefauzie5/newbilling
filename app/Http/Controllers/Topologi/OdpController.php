<?php

namespace App\Http\Controllers\Topologi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OdpController extends Controller
{
    public function index()
    {
        return view('odp/index');
    }
}
