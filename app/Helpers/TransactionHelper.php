<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class TransactionHelper
{
    public static function generateTransactionId($walletId = null)
    {
        $timestamp = now()->format('YmdHis');
        $randomStr = strtoupper(Str::random(6));
        $walletIdPart = $walletId ? str_pad($walletId, 3, '0', STR_PAD_LEFT) : '000';

        return 'CASH' . $timestamp . $walletIdPart . $randomStr;
    }
}