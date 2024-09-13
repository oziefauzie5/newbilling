<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Applikasi\SettingAplikasi;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        if (Auth::guard('web')->attempt($data)) {
            $idi = Auth::guard('web')->user()->id;
            $app = SettingAplikasi::first();

            if ($app) {
                $request->session()->put('app_brand', $app->app_brand);
                $request->session()->put('app_nama', $app->app_nama);
                $request->session()->put('app_logo', $app->app_logo);
                $request->session()->put('app_favicon', $app->app_favicon);
                $request->session()->put('app_npwp', $app->app_npwp);
                $request->session()->put('app_clientid', $app->app_clientid);
                $request->session()->put('app_alamat', $app->app_alamat);
                $request->session()->put('app_link_admin', $app->app_link_admin);
                $request->session()->put('app_link_pelanggan', $app->app_link_pelanggan);
            } else {
                $request->session()->put('app_brand', 'APPBILL');
                $request->session()->put('app_nama', 'APPBILL');
                $request->session()->put('app_logo', 'APPBILL');
                $request->session()->put('app_favicon', 'APPBILL');
                $request->session()->put('app_npwp', 'APPBILL');
                $request->session()->put('app_clientid', 'APPBILL-');
                $request->session()->put('app_alamat', 'Jl. Raya Bogor');
                $request->session()->put('app_link_admin', '-');
                $request->session()->put('app_link_pelanggan', '-');
            }





            $datas =  DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('users.id', $idi)
                ->first();

            if ($datas->role_id != 11 & $datas->role_id != 10 & $datas->role_id != 12 & $datas->role_id != 13) {
                return redirect()->route('admin.home');
            } elseif ($datas->role_id == 11) {
                return redirect()->route('admin.teknisi.index');
            } elseif ($datas->role_id == 10) {
                return redirect()->route('admin.biller.index');
            } elseif ($datas->role_id == 12) {
                return redirect()->route('admin.sales.index');
            } elseif ($datas->role_id == 13) {
                return redirect()->route('admin.biller.index');
            } else {
            }
        } else {
            return redirect()->route('adminapp')->with('failed', 'Username atau password salah');
        }
    }

    public function logout()
    {
        session()->forget('app_brand');
        session()->forget('app_nama');
        session()->forget('app_alamat');
        session()->forget('app_clientid');
        session()->forget('app_npwp');
        session()->forget('app_logo');
        session()->forget('app_favicon');
        session()->forget('app_link_admin');
        session()->forget('app_link_pelanggan');
        Auth::logout();
        return redirect()->route('adminapp')->with('success', 'Kamu berhasil logout');
    }
}
