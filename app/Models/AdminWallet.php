<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    protected $fillable = ['admin_id', 'balance'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function transactions()
    {
        return $this->hasMany(AdminWalletTransaction::class);
    }
}
