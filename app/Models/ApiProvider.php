<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    protected $fillable = [
        'name', 'api_token', 'base_url', 'status',
    ];
}
