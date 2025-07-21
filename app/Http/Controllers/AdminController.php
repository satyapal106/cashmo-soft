<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AdminWalletTransaction;
use App\Models\AdminWallet;
use App\Models\Admin;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    // Show Admin Login Page
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('admin/dashboard');
        }
        return view('admin.login');
    }

    // Handle Admin Login
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return redirect('admin/dashboard')->with('success', 'Welcome to the dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    // Show Admin Dashboard
    public function dashboard()
    {
        $apiToken = env('MRSPAY_API_TOKEN');

        $response = Http::get('https://mrspay.in/api/telecom/v1/check-balance', [
            'api_token' => $apiToken
        ]);

        if($response->successful()) {
            $data = $response->json();
            $amount = $data['balance']['normal_balance'] ?? '0.00';
            //return $amount;
        } else {
            $amount = 'Error fetching balance';
        }
        return view('admin.dashboard',compact('amount'));
    }

    public function profile()
    {
        return view('admin.profile');
    }


    public function index($adminId)
    {
        $wallet = AdminWallet::firstOrCreate(['admin_id' => $adminId]);
        $transactions = $wallet->transactions()->latest()->get();
        return view('admin.wallet.index', compact('wallet', 'transactions'));
    }


    // public function AddWalletAmount(Request $request)
    // {
    //     $request->validate([
    //         'admin_id' => 'required|exists:admins,id',
    //         'amount' => 'required|numeric|min:1',
    //         'type' => 'required|in:credit,debit',
    //         'status' => 'required|in:pending,success,failed,cancelled,refunded'
    //     ]);

    //     $wallet = AdminWallet::firstOrCreate(['admin_id' => $request->admin_id]);
    //     $before = $wallet->balance;

    //     if ($request->type == 'credit') {
    //         $wallet->balance += $request->amount;
    //     } elseif ($request->type == 'debit') {
    //         if ($wallet->balance < $request->amount) {
    //             return back()->with('error', 'Insufficient balance.');
    //         }
    //         $wallet->balance -= $request->amount;
    //     }

    //     $wallet->save();

    //     $wallet->transactions()->create([
    //         'type' => $request->type,
    //         'amount' => $request->amount,
    //         'before_balance' => $before,
    //         'after_balance' => $wallet->balance,
    //         'status' => $request->status,
    //         'description' => $request->description,
    //     ]);

    //     return back()->with('success', 'Transaction successful.');
    // }

    public function addWalletAmount(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'amount'   => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $wallet = AdminWallet::firstOrCreate(['admin_id' => $request->admin_id]);
            $before = $wallet->balance;
            $wallet->increment('balance', $request->amount);
            $txnId = 'TXN-' . now()->format('YmdHis') . '-' . Str::random(6);
            $wallet->transactions()->create([
                'transaction_id'  => $txnId,
                'type'            => 'credit',
                'amount'          => $request->amount,
                'before_balance'  => $before,
                'after_balance'   => $wallet->balance,
                'status'          => 'success',          // डिफ़ॉल्ट
                'description'     => $request->description,
            ]);
        });

        return back()->with('success', 'Amount added to wallet successfully.');
    }

    // Handle Admin Logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
