<?php

namespace App\Services;

use Firebase\JWT\JWT;

class TokenService
{
    protected $jwtKey;
    protected $partnerId;
    protected $aesKey;
    protected $aesIV;
    protected $authorisedKey;
    protected $environment;

    public function __construct()
    {
        $this->jwtKey = config('paysprint.jwt_key');
        $this->partnerId = config('paysprint.partner_id');
        $this->aesKey = config('paysprint.aes_key');
        $this->aesIV = config('paysprint.aes_iv');
        $this->authorisedKey = config('paysprint.authorised_key');
        $this->environment = config('paysprint.environment');
    }

    public function generateToken()
    {
        $payload = [
            'timestamp' => round(microtime(true) * 1000),
            'partnerId' => $this->partnerId,
            'reqid' => rand(100000, 999999)
        ];

        return JWT::encode($payload, $this->jwtKey, 'HS256');
    }

    public function getHeaders($token)
    {
        return [
            'Authorisedkey' => $this->authorisedKey,
            'Environment' => $this->environment,
            'Content-Type' => 'application/json',
            'Token' => $token,
            'accept' => 'application/json'
        ];
    }
}
