<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;

class Authentication extends Controller
{
    public static function getUserIdByToken($bearerToken)
    {
        return Jwt::JwtTokenPayload($bearerToken)->user_id;
    }
}