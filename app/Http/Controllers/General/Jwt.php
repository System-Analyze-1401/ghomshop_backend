<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Throwable;

class Jwt extends Controller
{
    public static function generateJwt($headers, $payload)
    {
        $headers_encoded = self::base64url_encode(json_encode($headers));
        $payload_encoded = self::base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", env('TOKEN_SECRET'), true);

        $signature_encoded = self::base64url_encode($signature);

        return $headers_encoded . "." . $payload_encoded . "." . $signature_encoded;
    }

    public static function base64url_encode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public static function isJwtValid($jwt)
    {
        try {
            // split the jwt
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signature_provided = $tokenParts[2];

            // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
            $expiration = json_decode($payload)->exp;
            $is_token_expired = ($expiration - time()) < 0;

            // build a signature based on the header and payload using the secret
            $base64_url_header = self::base64url_encode($header);
            $base64_url_payload = self::base64url_encode($payload);
            $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, env('TOKEN_SECRET'), true);
            $base64_url_signature = self::base64url_encode($signature);

            // verify it matches the signature provided in the jwt
            $is_signature_valid = ($base64_url_signature === $signature_provided);

            if ($is_token_expired || !$is_signature_valid)
                return false;
            else return true;
        } catch (Throwable $e)
        {
            return false;
        }
    }

    public static function JwtTokenPayload($jwt)
    {
        // split the jwt
        $tokenParts = explode('.', $jwt);
        $payload = base64_decode($tokenParts[1]);

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        return json_decode($payload);
    }

}
