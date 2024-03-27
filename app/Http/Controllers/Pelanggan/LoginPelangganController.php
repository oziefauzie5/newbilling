<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Applikasi\SettingAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Registrasi\Registrasi;
use Illuminate\Support\Facades\Session;

class LoginPelangganController extends Controller
{

    public function index()
    {
        return view('auth.login_pelanggan');
    }

    public function login_proses(Request $request)
    {

        $request->validate([
            'input_hp' => 'required',
        ]);


        $nomorhp = preg_replace("/[^0-9]/", "", $request->input_hp);
        if (!preg_match('/[^+0-9]/', trim($nomorhp))) {
            if (substr(trim($nomorhp), 0, 3) == '+62') {
                $nomorhp = trim($nomorhp);
            } elseif (substr($nomorhp, 0, 1) == '0') {
                $nomorhp = '' . substr($nomorhp, 1);
            }
        }

        $data = [
            'input_hp' => $nomorhp,
            'password' => $nomorhp,
        ];
        // dd($nomorhp);
        return redirect()->route('login_pelanggan')->with('failed', 'Nomor Whatsapp tidak terdaftar');
        if (Auth::guard('pelanggan')->attempt($data)) {
            $app = SettingAplikasi::first();

            $request->session()->put('app_brand', $app->app_brand);
            $request->session()->put('app_nama', $app->app_nama);
            $request->session()->put('app_logo', $app->app_logo);
            $request->session()->put('app_favicon', $app->app_favicon);
            return redirect()->route('client.index');
        } else {
            return redirect()->route('login_pelanggan')->with('failed', 'Nomor Whatsapp tidak terdaftar');
        }
    }
}
