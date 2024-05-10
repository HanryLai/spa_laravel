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
        'exp' => time() + 60 * 60, // 1 hour
    ];

    $encodedHeader = base64_encode(json_encode($header));
    $encodedPayload = base64_encode(json_encode($payload));

    $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, env('JWT_SECRET'));

    $token = $encodedHeader . '.' . $encodedPayload . '.' . $signature;

    return $token;
}
}