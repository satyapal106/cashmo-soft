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

    public function aepsTwoFactorKyc(Request $request)
    {
        $request->validate([
            'accessmodetype'  => 'required|in:APP,SITE',
            'adhaarnumber'    => 'required|digits_between:12,16',
            'mobilenumber'    => 'required|digits:10',
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'referenceno'     => 'required|string|max:50',
            'submerchantid'   => 'required|string|max:20',
            'is_iris'         => 'required|in:Yes,No,face_rd',
            'timestamp'       => 'required|date_format:Y-m-d H:i:s',
            'data'            => 'required|string', // XML string
            'ipaddress'       => 'required|ip',
        ]);

        $token = $this->tokenService->generateToken();

        $key = config('paysprint.aes_key');
        $iv  = config('paysprint.aes_iv');

        $referenceno = now()->format('YmdHis') . rand(10, 99);
        $ipaddress = $request->ip();
        $payload = [
            'accessmodetype' => "SITE",
            'adhaarnumber'   => $request->adhaarnumber,
            'mobilenumber'   => $request->mobilenumber,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'referenceno'    => $referenceno,
            'submerchantid'  => $request->submerchantid,
            'is_iris'        => $request->is_iris,
            'timestamp'      => $request->timestamp,
            'data'           => $request->data,
            'ipaddress'      => $ipaddress,
        ];

        $encryptedBody = \App\Helpers\EncryptHelper::encryptPayload($payload, $key, $iv);

        try {
            $client = new \GuzzleHttp\Client(['timeout' => 180]); // 3-minute timeout

            $response = $client->post('https://sit.paysprint.in/service-api/api/v1/service/aeps/kyc/Twofactorkyc/registration', [
                'headers' => [
                    'Token'         => $token,
                    'accept'        => 'application/json',
                    'content-type'  => 'application/json',
                ],
                'json' => [
                    'body' => $encryptedBody,
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API request failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
