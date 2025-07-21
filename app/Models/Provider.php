<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['service_id', 'provider_name', 'provider_code', 'logo', 'status'];

    protected $appends = ['api_data'];


    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function planCategory(){
        return $this->hasMany(PlanCategory::class);
    }

    public function rechargePlan(){
        return $this->hasMany(RechargePlan::class);
    }

    public function dthPlan(){
        return $this->hasMany(DTHplan::class);
    }

    public function slabs()
    {
        return $this->hasMany(Slab::class);
    }

    public function apiMappings()
    {
        return $this->hasMany(ApiProviderMapping::class, 'provider_id');
    }

        public function getApiDataAttribute()
    {
        // Ensure relationship is loaded
        if (!array_key_exists('apiMappings', $this->relations)) {
            $this->load('apiMappings');
        }

        $data = [];

        // You can cache this in controller if needed
        $apiProviders = ApiProvider::where('status', 1)->get();

        foreach ($apiProviders as $apiProvider) {
            $mapping = $this->apiMappings->firstWhere('api_provider_id', $apiProvider->id);
            $data[$apiProvider->id] = $mapping->api_id ?? '';
        }

        return $data;
    }

}
