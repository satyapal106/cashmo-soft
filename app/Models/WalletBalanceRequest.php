<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBalanceRequest extends Model
{
    protected $fillable = [
        'retailer_id', 'amount', 'status', 'remarks', 'transaction_id', 'screenshot'
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }
}
