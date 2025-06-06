<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi\Corporate;
use App\Models\Applikasi\SettingAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\PSB\InputData;
use Illuminate\Support\Facades\Session;

use App\Models\Registrasi\Registrasi;

class LoginPelangganController extends Controller
{

    public function index()
    {
         $settingapp = SettingAplikasi::first();
        if($settingapp){
            $data['logo'] = $settingapp->app_logo;
            $data['favicon'] = $settingapp->app_favicon;
            $data['brand'] = $settingapp->app_brand;
        } else{
            $data['logo'] = 'LOGO.png';
            $data['favicon'] = 'LOGO.png';
            $data['brand'] = 'APPBILL';

        }
        return view('auth.login_pelanggan',$data);
    }

    public function login_proses(Request $request)
    {
        $CORP_ID = Corporate::where('corp_url',url('/'))->first('id');
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


        if (Auth::guard('pelanggan')->attempt($data)) {
            $app = SettingAplikasi::first();

            $request->session()->put('app_brand', $app->app_brand);
            $request->session()->put('app_nama', $app->app_nama);
            $request->session()->put('app_logo', $app->app_logo);
            $request->session()->put('app_favicon', $app->app_favicon);
            $request->session()->put('corp_id', $CORP_ID->id);
            // return redirect()->route('client.index');
            return redirect()->route('client.maintenance');
        } else {
            return redirect()->route('login_pelanggan')->with('failed', 'Nomor Whatsapp tidak terdaftar');
        }
    }

    public function client_logout()
    {
        session()->forget('app_brand');
        session()->forget('app_nama');
        session()->forget('app_logo');
        session()->forget('app_favicon');
        session()->forget('corp_id');

        Auth::guard('pelanggan')->logout();
        return redirect()->route('login_pelanggan')->with('success', 'Kamu berhasil logout');
    }
}
