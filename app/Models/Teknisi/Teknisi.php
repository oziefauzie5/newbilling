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
        #-------Awal Update 15/01/2025--------
        // 'teknisi_nilai',
        // 'teknisi_nilai_instalasi',
        // 'teknisi_note',
        'teknisi_kode_kabel1',
        'teknisi_kode_before1',
        'teknisi_kode_after1',
        'teknisi_kode_kabel2',
        'teknisi_kode_before2',
        'teknisi_kode_after2',
        #-------Akhir Update 15/01/2025---------^
        'teknisi_pengecekan',
        'teknisi_note',
        'teknisi_status',
        'teknisi_noc_userid',
        'teknisi_keuangan_userid',
    ];
}
