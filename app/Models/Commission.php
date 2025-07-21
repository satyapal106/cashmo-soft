<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'package_id',
        'service_id',
        'provider_id',
        'slab_id',
        'nature',
        'type',
        'value',
    ];
}
