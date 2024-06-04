<?php

namespace App\Models\Teknisi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;
    protected $fillable = [
        'teknisi_id',
        'teknisi_userid',
        'teknisi_team',
        'teknisi_ket',
        'teknisi_job',
        'teknisi_idpel',
        'teknisi_psb',
        'teknisi_job_selesai',
        'teknisi_waktu_kerja',
        'teknisi_nilai',
        'teknisi_nilai_instalasi',
        'teknisi_note',
        'teknisi_pengecekan',
        'teknisi_note',
        'teknisi_status',
        'teknisi_noc_userid',
        'teknisi_keuangan_userid',
    ];
}
