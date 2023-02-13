<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\Jwt;
use App\Models\User;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class refreshTokenController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            ($request->all()),
            [
                'refresh_token' => 'required|exists:users,refresh_token',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function refreshToken(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $userId = User::getUserIdByUserRefreshToken($request['refresh_token']);

        $headers = array(
            'alg' => 'HS256',
            'typ' => 'JWT'
        );
        $payload = array(
            'user_id' => $userId,
            'exp' => (time() + (60 * env('ACCESS_TOKEN_EXPIRE_MINUTE'))),
            'iat' => time()
        );
        $refreshTokenPayload = array(
            'user_id' => $userId,
            'exp' => (time() + (60 * env('REFRESH_TOKEN_EXPIRE_MINUTE'))),
            'iat' => time()
        );

        $token = Jwt::generateJwt($headers, $payload);
        $refreshToken = Jwt::generateJwt($headers, $refreshTokenPayload);

        User::addRefreshToken($userId, $refreshToken);

        return CryptIO::encryptOutput(
            200,
            'توکن جدید ایجاد شد',
            [
                "access_token" => $token,
                "refresh_token" => $refreshToken
            ]
        );
    }
}