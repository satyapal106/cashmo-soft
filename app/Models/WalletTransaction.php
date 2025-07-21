<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'wallet_id',
        'type',
        'amount',
        'before_balance',
        'after_balance',
        'description',
        'status'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
