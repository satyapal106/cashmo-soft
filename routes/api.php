<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\PaysprintRechargeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::middleware(['auth:sanctum'])->group(function () {

//     Route::post('/mobile-recharge-payment', [MRSPayController::class, 'MobileRchargePayment']);
// });


Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/sign-in', [AuthController::class, 'signIn']);
Route::get('/service', [AuthController::class, 'Service']);
Route::get('/providers/{id}', [AuthController::class, 'getProvidersByServiceId']);
Route::get('/slabs/{provider_id}', [AuthController::class, 'getSlabsByProviderId']);
Route::get('/plan-categories/{provider_id}', [AuthController::class, 'getByProviderId']);
Route::get('/state', [AuthController::class, 'State']);
Route::get('/districts/{state_id}', [AuthController::class, 'getByStateId']);
Route::get('/languages', [AuthController::class, 'Language']);

Route::get('/generate-token', [JwtController::class, 'generateToken']);
Route::post('/check-balance', [JwtController::class, 'getMainBalance']);
Route::post('/get-cash-balance', [JwtController::class, 'getCashBalance']);
Route::post('/hlr-check', [JwtController::class, 'hlrCheck']);
Route::post('/dth-info', [JwtController::class, 'dthInfo']);
Route::get('/recharge/operators', [JwtController::class, 'getOperators']);
Route::post('/query-remitter', [JwtController::class, 'queryRemitter']);
Route::post('/bill/get-operators', [JwtController::class, 'getBillOperators']);
Route::post('/bill/fetch-bill', [JwtController::class, 'fetchBill']);
Route::post('/lpg-operatores', [JwtController::class, 'getLpgOperators']);
