<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SettingAkun extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'corporate_id',
        'akun_type',
        'akun_nama',
        'akun_rekening',
        'akun_pemilik',
        'akun_status',
        'akun_kategori',
    ];
    public function SettingAkun()
    {
        $SettingAkun = SettingAkun::where('corporate_id',Session::get('corp_id'))->where('akun_status', 'Enable');
        return $SettingAkun;
    }
}
