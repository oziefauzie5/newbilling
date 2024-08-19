<?php

namespace App\Models\Topologi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class odp extends Model
{
    use HasFactory;
    protected $fillable = [
        'odp_id',
        'odp_wilayah',
        'odp_maps',
        'odp_kooinat',
        'odp_port',
    ];
}
