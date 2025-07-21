<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWalletTransaction extends Model
{
    protected $fillable = [
        'admin_wallet_id',
        'type',
        'transaction_id',
        'amount',
        'before_balance',
        'after_balance',
        'status',
        'description',
    ];

    public function wallet()
    {
        return $this->belongsTo(AdminWallet::class, 'admin_wallet_id');
    }
}
