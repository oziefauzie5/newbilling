<?php

namespace App\Models\Applikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingAkun extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'akun_id',
        'akun_nama',
        'akun_rekening',
        'akun_pemilik',
        'akun_status',
    ];
    public function SettingAkun()
    {
        $SettingAkun = SettingAkun::where('akun_status', 'Enable')->get();

        return $SettingAkun;
    }
}
