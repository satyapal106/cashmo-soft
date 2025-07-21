<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProviderMapping extends Model
{
    
    protected $fillable = ['provider_id', 'api_provider_id', 'api_id'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function apiProvider()
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }
}
