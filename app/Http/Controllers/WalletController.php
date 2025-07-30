<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletBalanceRequest;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;


class WalletController extends Controller
{
    public function UserWallet()
    {
        $title = "Retailer Wallet";
        $user_id = Auth::guard('retailer')->user()->id;
        $wallet = Wallet::where('user_id', $user_id)->with('transactions')->first();
        //return $wallet;
        return view('retailer.wallet', compact('user_id', 'wallet', 'title'));
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


    public function showForm()
    {
        $title = "Retailer Wallet Request";
        return view('retailer.wallet_request_form', compact('title'));
    }



    public function sendRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'transaction_id' => 'required|string|max:100',
            'screenshot' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
            'remarks' => 'nullable|string|max:255',
        ]);

        $screenshotPath = null;

        if ($request->hasFile('screenshot')) {
            $manager = new ImageManager(new Driver());
            $path = 'assets/images/screenshots/';

            if (!is_dir(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            $uploadedImage = $request->file('screenshot');
            $image = $manager->read($uploadedImage);
            $image->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->encode(new WebpEncoder(quality: 65));
            $filename = uniqid() . '.webp';
            $image->save(public_path($path . $filename));

            $screenshotPath = $path . $filename;
        }

        $user_id = Auth::guard('retailer')->id();

        WalletBalanceRequest::create([
            'retailer_id' => $user_id,
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $screenshotPath,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Wallet balance request sent successfully.');
    }


    public function RetailerBalanceRequest()
    {
        $requests = WalletBalanceRequest::with('retailer')->orderBy('created_at', 'desc')->get();
        return view('admin.retailer.wallet-request', compact('requests'));
    }

       // Admin approves request
    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $request = WalletBalanceRequest::lockForUpdate()->findOrFail($id);

            if ($request->status !== 'pending') {
                abort(400, 'Request already processed.');
            }

            $retailer = $request->retailer;
            $retailer->wallet_balance += $request->amount;
            $retailer->save();

            $request->status = 'approved';
            $request->remarks = 'Approved by admin';
            $request->save();
        });

        return response()->json(['message' => 'Request approved and wallet updated.']);
    }

    // Admin rejects request
    public function reject(Request $request, $id)
    {
        $walletRequest = WalletBalanceRequest::findOrFail($id);

        if ($walletRequest->status !== 'pending') {
            return response()->json(['message' => 'Request already processed.'], 400);
        }

        $walletRequest->status = 'rejected';
        $walletRequest->remarks = $request->remarks ?? 'Rejected by admin';
        $walletRequest->save();

        return response()->json(['message' => 'Request rejected.']);
    }
}
