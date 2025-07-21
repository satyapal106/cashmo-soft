<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TokenService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class JwtController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    // === 1. Token generation endpoint ===
    public function generateToken()
    {
        try {
            $token = $this->tokenService->generateToken();
    
            return response()->json([
                'status' => true,
                'message' => 'Token generated successfully',
                'token' => $token
            ], 200); // HTTP 200 OK
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate token',
                'error' => $e->getMessage()
            ], 500); // HTTP 500 Internal Server Error
        }
    }
    

    // === 2. Use token from request headers ===

    public function getMainBalance(Request $request)
    {
        $token = $request->header('Token');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/balance/balance/mainbalance', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => []
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCashBalance(Request $request)
    {
        $token = $request->header('Token');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/balance/balance/cashbalance', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => []
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function hlrCheck(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|numeric',
            'type' => 'required|string|in:mobile,landline'
        ]);

        $token = $request->header('Token');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/recharge/hlrapi/hlrcheck', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'number' => $validated['number'],
                    'type' => $validated['type']
                ]
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function dthInfo(Request $request)
    {
        $validated = $request->validate([
            'canumber' => 'required|string',
            'op' => 'required|string',
        ]);

        $token = $request->header('Token');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/recharge/hlrapi/dthinfo', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'canumber' => $validated['canumber'],
                    'op' => $validated['op'],
                ]
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOperators(Request $request)
    {
        $token = $request->header('Token');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/recharge/recharge/getoperator', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => []
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function queryRemitter(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);

        $token = $request->header('Token');
        $mobile = $request->input('mobile');
        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/dmt/kyc/remitter/queryremitter', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => ['mobile' => $mobile]
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('Remitter query failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to query remitter', 'details' => $e->getMessage()], 500);
        }
    }

    public function queryRemitterKyc(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'mobile'         => 'required|digits:10',
            'lat'            => 'required|string',
            'long'           => 'required|string',
            'aadhaar_number' => 'required|digits:12',
            'data'           => 'required|string',
            'is_iris'        => 'required|integer|in:0,1,2', // depending on API expected values
        ]);

        $token = $request->header('Token');

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/dmt/kyc/remitter/queryremitter/kyc', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'mobile'         => $validated['mobile'],
                    'lat'            => $validated['lat'],
                    'long'           => $validated['long'],
                    'aadhaar_number' => $validated['aadhaar_number'],
                    'data'           => $validated['data'],
                    'is_iris'        => $validated['is_iris'],
                ],
            ]);

            // Return the decoded JSON response from the external API
            return response()->json(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            \Log::error('Query Remitter KYC API failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to query remitter KYC', 'details' => $e->getMessage()], 500);
        }
    }

    public function registerRemitter(Request $request)
    {
        // Validate input fields for this API
        $validated = $request->validate([
            'mobile'    => 'required|digits:10',
            'otp'       => 'required|string',
            'stateresp' => 'required|string',
            'ekyc_id'   => 'required|string',
        ]);

        $token = $request->header('Token'); // token from request header

        $client = new Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/dmt/kyc/remitter/registerremitter', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'mobile'    => $validated['mobile'],
                    'otp'       => $validated['otp'],
                    'stateresp' => $validated['stateresp'],
                    'ekyc_id'   => $validated['ekyc_id'],
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            \Log::error('Register Remitter API failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to register remitter', 'details' => $e->getMessage()], 500);
        }
    }

    public function getBillOperators(Request $request)
    {
        $token = $request->header('Token');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 400);
        }

        $client = new \GuzzleHttp\Client();
    
        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/bill-payment/bill/getoperator', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'mode' => 'online/offline',
                ],
            ]);
    
            // Return JSON-decoded response
            return response()->json(json_decode($response->getBody(), true));
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch operators',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function fetchBill(Request $request)
    {
        $token = $request->header('Token');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 400);
        }

        // Validation (as needed)
        $validated = $request->validate([
            'operator' => 'required|integer',
            'canumber' => 'required|string',
            'mode' => 'required|string',
            'ad1' => 'nullable|string', // Optional, depends on operator
        ]);

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/bill-payment/bill/fetchbill', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => $validated,
            ]);

            return response()->json(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch bill',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getLpgOperators(Request $request)
    {
        $token = $request->header('Token');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 400);
        }

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/bill-payment/lpg/getoperator', [
                'headers' => $this->tokenService->getHeaders($token),
                'json' => [
                    'mode' => 'online/offline',
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'error' => 'Failed to fetch LPG operators',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
