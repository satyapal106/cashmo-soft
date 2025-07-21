<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function UserWallet()
    {
        $title ="Retailer Wallet";
        $user_id = Auth::guard('retailer')->user()->id;
        $wallet = Wallet::where('user_id', $user_id)->with('transactions')->first();
        //return $wallet;
         return view('retailer.wallet', compact('user_id','wallet', 'title'));
    }

    public function addBalance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1'
        ]);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user_id],
            ['balance' => 0]
        );

        $before = $wallet->balance;
        $wallet->balance += $request->amount;
        $wallet->save();

        WalletTransaction::create([
            'transaction_id' => TransactionHelper::generateTransactionId($wallet->id),
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'before_balance' => $before,
            'after_balance' => $wallet->balance,
            'description' => $request->description,
            'status' => 'success'
        ]);

        return back()->with('success', 'Wallet balance updated');
    }

}
