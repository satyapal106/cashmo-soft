<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function planCategories() {
        return $this->hasMany(PlanCategory::class);
    } 
    
}
