<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'provider_id',
        'states',
        'amount',
        'plan_name',
        'validity',
        'time_duration',
        'other_duration',
        'calling_options',
        'data',
        'data_renewal',
        'other_data_renewal',
        'unlimited_5g',
        'sms_count',
        'sms_renewal',
        'plan_category',
        'additional_benefits',
    ];

    protected $casts = [
        'states' => 'array',
        'plan_category' => 'array',
    ];

    public function provider() {
        return $this->belongsTo(Provider::class);
    }

    public function planCategory() {
        return $this->belongsTo(PlanCategory::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

}
