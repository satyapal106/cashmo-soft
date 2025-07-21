<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCategory extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','provider_id', 'name', 'status'];

    public function provider() {
        return $this->belongsTo(Provider::class);
    }
}
