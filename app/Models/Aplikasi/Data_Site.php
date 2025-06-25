<?php

namespace App\Models\Aplikasi;

use App\Models\Router\Router;
use App\Models\Teknisi\Data_pop;
use App\Models\Gudang\gudang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Data_Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'corporate_id',
        'site_prefix',
        'site_nama',
        'site_status',
    ];

     function site_mitra()
    {
        return $this->belongsTo(Data_Site::class,'id','data_site_id');
    }
     function site_pop()
    {
        return $this->belongsTo(Data_pop::class,'id','data__site_id');
    }
    function site_gudang()
    {
        return $this->HasMany(Gudang::class,'id','data__site_id');
    }

   function site_router()
    {
        // data__sites
        // id - integer
        // site_nama - string
      
        // data_pops
        // id - integer
        // data__site_id - string
        // pop_nama - string

        // routers
        // id - integer
        // data_pop_id - integer
        // router_nama - string



       return $this->hasManyThrough(
            Data_pop::class,
            Router::class,
            'data_pop_id', // Foreign key on the deployments table...
            'data__site_id', // Foreign key on the environments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

//     projects
//     id - integer
//     name - string
 
//     environments
//     id - integer
//     project_id - integer
//     name - string
 
//     deployments
//     id - integer
//     environment_id - integer
//     commit_hash - string



    //  return $this->hasManyThrough(
    //         Deployment::class,
    //         Environment::class,
    //         'project_id', // Foreign key on the environments table...
    //         'environment_id', // Foreign key on the deployments table...
    //         'id', // Local key on the projects table...
    //         'id' // Local key on the environments table...
    //     );

}
