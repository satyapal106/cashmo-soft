<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $fillable = [
    'user_id', 'number', 'amount', 'provider_id','service_id','status', 'operator_ref', 'payid', 'message',
    'transaction_id', 'invoice_id', 'cashmo_id', 'optional_fields'
    ];

    protected $casts = [
        'optional_fields' => 'array',
    ];

}
