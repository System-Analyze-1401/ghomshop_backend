<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\Jwt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class loginUserController extends Controller
{
    protected function validatorRegister($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|max:250',
                'last_name' => 'required|string|max:250',
                'address' => 'required|string',
                'phone_number' => 'required|iran_mobile|unique:users',
                'email_address' => 'required|email|unique:users',
                'password' => 'required|string|between:8,32',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function register(Request $request)
    {
        $validateFails = $this->validatorRegister($request);
        if ($validateFails)
            return $validateFails;

        $userId = User::register($request);

        return CryptIO::encryptOutput(200, 'حساب کاربری با موفقیت ایجاد شد.', $userId);
    }

    protected function validatorLogin($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'email_address' => 'required|email|exists:users,email_address',
                'password' => 'required|string|between:8,32',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function login(Request $request)
    {
        $validateFails = $this->validatorLogin($request);
        if ($validateFails)
            return $validateFails;

        $userId = User::getUserIdWithEmailAddress($request['email_address']);

        $user = User::descriptionUser($userId);

        if ($user['password'] != $request['password'])
            return CryptIO::encryptOutput(400, 'رمز وارد شده اشتباه است.');

        $headers = array(
            'alg' => 'HS256',
            'typ' => 'JWT'
        );

        $payload = array(
            'user_id' => $user['id'],
            'role' => $user['role'],
            'exp' => (time() + (60 * env('ACCESS_TOKEN_EXPIRE_MINUTE'))),
            'iat' => time()
        );

        $refreshTokenPayload = array(
            'user_id' => $user['id'],
            'role' => $user['role'],
            'exp' => (time() + (60 * env('REFRESH_TOKEN_EXPIRE_MINUTE'))),
            'iat' => time()
        );

        $token = Jwt::generateJwt($headers, $payload);
        $refreshToken = Jwt::generateJwt($headers, $refreshTokenPayload);

        User::addRefreshToken($user['id'], $refreshToken);

        return CryptIO::encryptOutput(
            200,
            'با موفقیت وارد حساب کاربری شدید.',
            [
                "access_token" => $token,
                "refresh_token" => $refreshToken
            ]
        );
    }
}