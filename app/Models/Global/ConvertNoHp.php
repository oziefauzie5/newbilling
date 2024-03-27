<?php

namespace App\Models\Global;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvertNoHp extends Model
{
    use HasFactory;
    public function convert_nohp($nohp)
    {
        $nomorhp = preg_replace("/[^0-9]/", "", $nohp);
        if (!preg_match('/[^+0-9]/', trim($nomorhp))) {
            if (substr(trim($nomorhp), 0, 3) == '+62') {
                $nomorhp = trim($nomorhp);
            } elseif (substr($nomorhp, 0, 1) == '0') {
                $nomorhp = '' . substr($nomorhp, 1);
            }
        }
        return $nomorhp;
    }
}
