<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JWTController extends Controller
{
    function generateJWT($userId,$role)
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $payload = [
            'iss' => 'your_issuer',
            'user_id' => $userId,
            'role'=>$role,
            'iat' => time(),
            'exp' => time() + 10, // 1 hour
        ];
        

        $encodedHeader = base64_encode(json_encode($header));
        $encodedPayload = base64_encode(json_encode($payload));

        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, env('JWT_SECRET'));

        $token = $encodedHeader . '.' . $encodedPayload . '.' . $signature;

        return $token;
    }

    function verityJWT($token_input){
        $tokenParts = explode('.', $token_input);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];
        
        $header = json_decode($header, JSON_OBJECT_AS_ARRAY);

        $payload = json_decode($payload, JSON_OBJECT_AS_ARRAY);
        
        $signature = hash_hmac('sha256', $tokenParts[0] . '.' . $tokenParts[1], env('JWT_SECRET'));

        if ($signature !== $signatureProvided) {
            return false;
        }

        $time = time();
        if ($time < $payload['iat'] || $time > $payload['exp']) {
            return false;
        }

        return $payload;
    }

    function getPayload($token_input){
        $tokenParts = explode('.', $token_input);
        $payload = base64_decode($tokenParts[1]);
        $payload = json_decode($payload, JSON_OBJECT_AS_ARRAY);
        return $payload;
    }
}