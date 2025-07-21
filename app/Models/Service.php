<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = ['service_name', 'service_code', 'status'];

    public function provider(){
        return $this->hasMany(Provider::class);
    }

    public function slabs()
    {
        return $this->hasMany(Slab::class);
    }
    
}
