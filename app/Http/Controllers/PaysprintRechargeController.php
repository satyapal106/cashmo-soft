<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TokenService;
use GuzzleHttp\Client;

class PaysprintRechargeController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function getOperators()
    {
        $token = $this->tokenService->generateToken();
        
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/recharge/recharge/getoperator', [
                'headers' => [
                    'Authorisedkey' => config('paysprint.authorised_key'),
                    'Token' => $token,
                    'accept' => 'application/json',
                ],
                'json' => [] // Assuming the body is empty as per your original code
            ]);

            $result = json_decode($response->getBody(), true);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function onboardMerchant(Request $request)
    {
        $request->validate([
            'mobile'   => 'required|digits:10',
            'is_new'   => 'required|in:0,1',
            'email'    => 'required|email',
            'firm'     => 'required|string|max:255',
            'callback' => 'required|url',
        ]);

        $merchantCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $token = $this->tokenService->generateToken(); 
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/onboard/onboardnew/getonboardurl', [
                'headers' => [
                    'Authorisedkey' => config('paysprint.authorised_key'),
                    'Token'         => $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'merchantcode' => $merchantCode,
                    'mobile'       => $request->mobile,
                    'is_new'       => $request->is_new,
                    'email'        => $request->email,
                    'firm'         => $request->firm,
                    'callback'     => $request->callback,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
