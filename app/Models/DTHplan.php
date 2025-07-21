<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DTHplan extends Model
{

    protected $table = "dth_plans";
    
    protected $fillable = [
        'service_id',
        'provider_id', 
        'amount',
        'plan_name',
        'validity',
        'languages',
        'channel_quality',
        'benefits',
        'channel_summary'
    ];

    public function provider() {
        return $this->belongsTo(Provider::class);
    }
}
