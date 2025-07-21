<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
    use HasFactory;

    // Define the table name (optional if it's not pluralized)
    protected $table = 'service_lists';

    // Mass assignable attributes
    protected $fillable = [
        'cashmo_id', 'provider_id', 'provider_name','service_id', 'service_name', 'provider_icon', 'min', 'max', 'status'
    ];
}
