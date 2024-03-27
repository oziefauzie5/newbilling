<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_Has_Role extends Model
{
    use HasFactory;
    public $table = "model_has_roles";
    public $timestamps = false;
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];
}
