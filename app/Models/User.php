<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Aplikasi\Data_Site;
use App\Models\Mitra\Mitra_Sub;
use App\Models\Mitra\MitraSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'corporate_id',
        'name',
        'username',
        'email',
        'password',
        'ktp',
        'hp',
        'photo',
        'data__site_id',
        'alamat_lengkap',
        'tgl_gabung',
        'status_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //  function user_role()
    // {
    //     return $this->hasMany(Model_Has_Role::class,'model_id','id');
    // }
     function user_mitra()
    {
        return $this->hasOne(MitraSetting::class,'mts_user_id','id');
    }
    function user_site()
    {
        return $this->hasOne(Data_Site::class,'id','data__site_id');
    }
    function user_submitra()
    {
        return $this->hasOne(Mitra_Sub::class,'mts_sub_user_id','id');
    }
   


    
}
