<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use App\Models\Hotspot\Data_Voucher;
use Illuminate\Http\Request;

class PostVoucherController extends Controller
{
    public function handle(Request $request)
    {
        $date_mik = explode("/", $request->tgl_pakai);
        if($date_mik[0] == 'jan'){
            $tgl_pakai = date($date_mik[2].'-01-'.$date_mik[1]);
        } elseif ($date_mik[0] == 'feb'){
            $tgl_pakai = date($date_mik[2].'-02-'.$date_mik[1]);
        } elseif($date_mik[0] == 'mar'){
            $tgl_pakai = date($date_mik[2].'-03-'.$date_mik[1]);
        } elseif($date_mik[0] == 'apr'){
            $tgl_pakai = date($date_mik[2].'-04-'.$date_mik[1]);
        } elseif($date_mik[0] == 'may'){
            $tgl_pakai = date($date_mik[2].'-05-'.$date_mik[1]);
        } elseif($date_mik[0] == 'jun'){
            $tgl_pakai = date($date_mik[2].'-06-'.$date_mik[1]);
        } elseif($date_mik[0] == 'jul'){
            $tgl_pakai = date($date_mik[2].'-07-'.$date_mik[1]);
        } elseif($date_mik[0] == 'aug'){
            $tgl_pakai = date($date_mik[2].'-08-'.$date_mik[1]);
        } elseif($date_mik[0] == 'sep'){
            $tgl_pakai = date($date_mik[2].'-09-'.$date_mik[1]);
        } elseif($date_mik[0] == 'oct'){
            $tgl_pakai = date($date_mik[2].'-10-'.$date_mik[1]);
        } elseif($date_mik[0] == 'nov'){
            $tgl_pakai = date($date_mik[2].'-11-'.$date_mik[1]);
        } elseif($date_mik[0] == 'dec'){
            $tgl_pakai = date($date_mik[2].'-12-'.$date_mik[1]);

        } 
        $date_exp = explode("/", substr($request->waktu_exp, 0, 11));
        $time_exp = substr($request->waktu_exp, 12, 20);
        if($date_exp[0] == 'jan'){
            $tgl_exp = date($date_exp[2].'-01-'.$date_exp[1]);
        } elseif ($date_exp[0] == 'feb'){
            $tgl_exp = date($date_exp[2].'-02-'.$date_exp[1]);
        } elseif($date_exp[0] == 'mar'){
            $tgl_exp = date($date_exp[2].'-03-'.$date_exp[1]);
        } elseif($date_exp[0] == 'apr'){
            $tgl_exp = date($date_exp[2].'-04-'.$date_exp[1]);
        } elseif($date_exp[0] == 'may'){
            $tgl_exp = date($date_exp[2].'-05-'.$date_exp[1]);
        } elseif($date_exp[0] == 'jun'){
            $tgl_exp = date($date_exp[2].'-06-'.$date_exp[1]);
        } elseif($date_exp[0] == 'jul'){
            $tgl_exp = date($date_exp[2].'-07-'.$date_exp[1]);
        } elseif($date_exp[0] == 'aug'){
            $tgl_exp = date($date_exp[2].'-08-'.$date_exp[1]);
        } elseif($date_exp[0] == 'sep'){
            $tgl_exp = date($date_exp[2].'-09-'.$date_exp[1]);
        } elseif($date_exp[0] == 'oct'){
            $tgl_exp = date($date_exp[2].'-10-'.$date_exp[1]);
        } elseif($date_exp[0] == 'nov'){
            $tgl_exp = date($date_exp[2].'-11-'.$date_exp[1]);
        } elseif($date_exp[0] == 'dec'){
            $tgl_exp = date($date_exp[2].'-12-'.$date_exp[1]);

        } 
        // dd($tgl_exp.' '.$time_exp);
        Data_Voucher::where('vhc_username',$request->name)->update([
            'vhc_tgl_jual' => $tgl_pakai.' '.$request->waktu_pakai,
            'vhc_exp' => $tgl_exp.' '.$time_exp,
            'vhc_mac' => $request->mac,
        ]);



        return 'berhasih';
    }
}
