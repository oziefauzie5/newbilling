<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'corporate_id',
        'user_id',
        'teknisi_idpel',
        'teknisi_team',
        'teknisi_ket',
        'teknisi_job',
        'teknisi_psb',
        'teknisi_status',
        'created_at',
        'updated_at',
    ];
    // public $timestamps = false;
}
