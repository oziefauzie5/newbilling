<?php

namespace App\Http\Controllers\Topologi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopologiController extends Controller
{
    public function index()
    {
        return view('topologi/clousur');
    }
}
