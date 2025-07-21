<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slab extends Model
{
    protected $fillable = ['name','min_amount', 'max_amount', 'service_id', 'provider_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
