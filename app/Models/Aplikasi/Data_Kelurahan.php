<?php

namespace App\Models\Aplikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Data_Kelurahan extends Model
{
    use HasFactory;
      protected $fillable = [
        'kel_id',
        'corporate_id',
        'kel_site_id',
        'kel_nama',
        'kel_ket',
        'kel_status',
    ];
    /**
     * Get all of the comments for the Data_Kelurahan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    // public function site():HasMany 
    // {
    //     return $this->hasMany(Data_Site::class,'site_id','kel_site_id');
    // }

    // public function kelurahan(): HasMany
    // {
    //     return $this->hasMany(Data_Kelurahan::class, 'kel_site_id', 'site_id');
    // }

        
}
