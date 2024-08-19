<?php

namespace App\Models\Topologi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class crousur extends Model
{
    use HasFactory;
    protected $fillable = [
        'clous_id',
        'clous_maps',
        'clous_kooinat',
        'clous_core',
    ];
}
