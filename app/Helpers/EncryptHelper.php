<?php
namespace App\Helpers;

class EncryptHelper
{
    public static function encryptPayload(array $data, string $key, string $iv): string
    {
        $cipher = openssl_encrypt(json_encode($data), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($cipher);
    }
}






