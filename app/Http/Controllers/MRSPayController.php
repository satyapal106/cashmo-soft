<?php

namespace App\Http\Controllers;

use App\Models\Recharge;
use App\Models\User;
use App\Models\Commission;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TransactionHelper;

class MRSPayController extends Controller
{
    public function MobileRechargePayment1(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'amount' => 'required|numeric',
            'provider_id' => 'required|integer',
            'state_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::guard('retailer')->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            $wallet = DB::table('wallets')->where('user_id', $user->id)->lockForUpdate()->first();

            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found.'], 404);
            }

            $packageId = $user->package_id;
            $serviceId = 1;
            $providerId = $request->provider_id;
            $amount = $request->amount;

            $commission = Commission::where('package_id', $packageId)
                ->where('service_id', $serviceId)
                ->where('provider_id', $providerId)
                ->first();

            $commissionAmount = 0;
            $totalAmount = $amount;

            if ($commission && $commission->nature === 'charge') {
                $commissionAmount = $commission->type === '%' ? ($commission->value / 100) * $amount : $commission->value;
                $totalAmount += $commissionAmount;
            }

            if ($wallet->balance < $totalAmount) {
                return response()->json(['message' => 'Insufficient wallet balance.'], 400);
            }

            $beforeBalance = $wallet->balance;
            $afterBalance = $beforeBalance - $totalAmount;

            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $afterBalance]);

            $transactionId = TransactionHelper::generateTransactionId($wallet->id);
            $invoiceId = 'INV' . now()->format('YmdHis') . rand(100, 999);
            $cashmoId = 'CASHMO-' . rand(100000, 999999);

            $response = Http::get('https://mrspay.in/api/telecom/v1/payment', [
                'api_token' => env('MRSPAY_API_TOKEN'),
                'number' => $request->mobile_number,
                'amount' => $amount,
                'provider_id' => $providerId,
                'client_id' => $user->id,
            ]);

            $data = $response->json();

            $recharge = Recharge::create([
                'user_id' => $user->id,
                'number' => $request->mobile_number,
                'amount' => $amount,
                'provider_id' => $providerId,
                'service_id' => $serviceId,
                'status' => $data['status'] ?? 'unknown',
                'operator_ref' => $data['operator_ref'] ?? null,
                'payid' => $data['payid'] ?? null,
                'transaction_id' => $transactionId,
                'invoice_id' => $invoiceId,
                'cashmo_id' => $cashmoId,
                'message' => $data['message'] ?? null,
            ]);

            WalletTransaction::create([
                'transaction_id' => $transactionId,
                'wallet_id' => $wallet->id,
                'amount' => $totalAmount,
                'before_balance' => $beforeBalance,
                'after_balance' => $afterBalance,
                'type' => 'debit',
                'description' => 'Recharge for ₹' . $amount . ($commissionAmount ? ' + charge ₹' . $commissionAmount : ''),
                'recharge_id' => $recharge->id,
                'status' => 'success',
            ]);

            if ($data['status'] === 'success') {
                if ($commission && $commission->nature === 'cashback') {
                    $cashbackAmount = $commission->type === '%' ? ($commission->value / 100) * $amount : $commission->value;

                    $before = DB::table('wallets')->where('user_id', $user->id)->value('balance');
                    $after = $before + $cashbackAmount;

                    DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $after]);

                    WalletTransaction::create([
                        'transaction_id' => TransactionHelper::generateTransactionId($wallet->id),
                        'wallet_id' => $wallet->id,
                        'amount' => $cashbackAmount,
                        'before_balance' => $before,
                        'after_balance' => $after,
                        'type' => 'credit',
                        'description' => 'Recharge cashback ₹' . $cashbackAmount,
                        'recharge_id' => $recharge->id,
                        'status' => 'success',
                    ]);
                }
            } elseif ($data['status'] === 'failure') {
                $before = DB::table('wallets')->where('user_id', $user->id)->value('balance');
                $after = $before + $totalAmount;

                DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $after]);

                WalletTransaction::create([
                    'transaction_id' => TransactionHelper::generateTransactionId($wallet->id),
                    'wallet_id' => $wallet->id,
                    'amount' => $totalAmount,
                    'before_balance' => $before,
                    'after_balance' => $after,
                    'type' => 'credit',
                    'description' => 'Refund due to recharge failure',
                    'recharge_id' => $recharge->id,
                    'status' => 'success',
                ]);
            }

            DB::commit();

            $finalBalance = DB::table('wallets')->where('user_id', $user->id)->value('balance');

            return response()->json([
                'success' => true,
                'message' => 'Recharge processed.',
                'transaction_id' => $transactionId,
                'invoice_id' => $invoiceId,
                'cashmo_id' => $cashmoId,
                'wallet_balance' => $finalBalance,
                'api_response' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function MobileRechargePayment(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'amount' => 'required|numeric',
            'provider_id' => 'required|integer',
            'state_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::guard('retailer')->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            $wallet = DB::table('wallets')->where('user_id', $user->id)->lockForUpdate()->first();

            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found.'], 404);
            }

            $packageId = $user->package_id;
            $serviceId = 1;
            $providerId = $request->provider_id;
            $amount = $request->amount;

            $commission = Commission::where('package_id', $packageId)
                ->where('service_id', $serviceId)
                ->where('provider_id', $providerId)
                ->first();

            $commissionAmount = 0;
            $totalAmount = $amount;

            if ($commission && $commission->nature === 'charge') {
                $commissionAmount = $commission->type === '%' ? ($commission->value / 100) * $amount : $commission->value;
                $totalAmount += $commissionAmount;
            }

            if ($wallet->balance < $totalAmount) {
                return response()->json(['message' => 'Insufficient wallet balance.'], 400);
            }

            $beforeBalance = $wallet->balance;
            $afterBalance = $beforeBalance - $totalAmount;

            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $afterBalance]);

            $transactionId = TransactionHelper::generateTransactionId($wallet->id);
            $invoiceId = 'INV' . now()->format('YmdHis') . rand(100, 999);
            $cashmoId = 'CASHMO-' . rand(100000, 999999);

            $apiMapping = DB::table('api_provider_mappings')
                ->where('provider_id', $providerId)
                ->first();

            if (!$apiMapping) {
                DB::rollBack();
                return response()->json(['message' => 'API mapping not found.'], 404);
            }

            $apiProvider = DB::table('api_providers')
                ->where('id', $apiMapping->api_provider_id)
                ->where('status', 1)
                ->first();

            if (!$apiProvider) {
                DB::rollBack();
                return response()->json(['message' => 'API provider not found or inactive.'], 404);
            }

            // 🌐 Call External API
            $response = Http::get('https://mrspay.in/api/telecom/v1/payment', [
                'api_token' => env('MRSPAY_API_TOKEN'),
                'number' => $request->mobile_number,
                'amount' => $amount,
                'provider_id' => $apiMapping->api_id,
                'client_id' => $user->id,
            ]);

            $data = $response->json();

            // 🧾 Recharge log
            $recharge = Recharge::create([
                'user_id' => $user->id,
                'number' => $request->mobile_number,
                'amount' => $amount,
                'provider_id' => $providerId,
                'service_id' => $serviceId,
                'status' => $data['status'] ?? 'unknown',
                'operator_ref' => $data['operator_ref'] ?? null,
                'payid' => $data['payid'] ?? null,
                'transaction_id' => $transactionId,
                'invoice_id' => $invoiceId,
                'cashmo_id' => $cashmoId,
                'message' => $data['message'] ?? null,
            ]);

            // 💳 Debit transaction
            WalletTransaction::create([
                'transaction_id' => $transactionId,
                'wallet_id' => $wallet->id,
                'amount' => $totalAmount,
                'before_balance' => $beforeBalance,
                'after_balance' => $afterBalance,
                'type' => 'debit',
                'description' => 'Recharge for ₹' . $amount . ($commissionAmount ? ' + charge ₹' . $commissionAmount : ''),
                'recharge_id' => $recharge->id,
                'status' => 'success',
            ]);

            // ✅ Recharge success: apply cashback
            if ($data['status'] === 'success') {
                if ($commission && $commission->nature === 'cashback') {
                    $cashbackAmount = $commission->type === '%' ? ($commission->value / 100) * $amount : $commission->value;

                    $before = DB::table('wallets')->where('user_id', $user->id)->value('balance');
                    $after = $before + $cashbackAmount;

                    DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $after]);

                    WalletTransaction::create([
                        'transaction_id' => TransactionHelper::generateTransactionId($wallet->id),
                        'wallet_id' => $wallet->id,
                        'amount' => $cashbackAmount,
                        'before_balance' => $before,
                        'after_balance' => $after,
                        'type' => 'credit',
                        'description' => 'Recharge cashback ₹' . $cashbackAmount,
                        'recharge_id' => $recharge->id,
                        'status' => 'success',
                    ]);
                }
            }

            // ❌ Recharge failed: refund amount
            elseif ($data['status'] === 'failure') {
                $before = DB::table('wallets')->where('user_id', $user->id)->value('balance');
                $after = $before + $totalAmount;

                DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $after]);

                WalletTransaction::create([
                    'transaction_id' => TransactionHelper::generateTransactionId($wallet->id),
                    'wallet_id' => $wallet->id,
                    'amount' => $totalAmount,
                    'before_balance' => $before,
                    'after_balance' => $after,
                    'type' => 'credit',
                    'description' => 'Refund due to recharge failure',
                    'recharge_id' => $recharge->id,
                    'status' => 'success',
                ]);
            }

            DB::commit();

            $finalBalance = DB::table('wallets')->where('user_id', $user->id)->value('balance');

            return response()->json([
                'success' => true,
                'message' => 'Recharge processed.',
                'transaction_id' => $transactionId,
                'invoice_id' => $invoiceId,
                'cashmo_id' => $cashmoId,
                'wallet_balance' => $finalBalance,
                'api_response' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
