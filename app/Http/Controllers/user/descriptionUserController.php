<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\Authentication;
use App\Http\Controllers\General\CryptIO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class descriptionUserController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:users',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function description(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $result = User::descriptionUser($request['id']);

        return CryptIO::encryptOutput(200, null, $result);
    }

    protected function currentUser(Request $request)
    {
        $userId = Authentication::getUserIdByToken($request->bearerToken());

        $user = User::descriptionUser($userId);

        unset($user['password']);
        unset($user['refresh_token']);

        return CryptIO::encryptOutput(200, null, $user);
    }
}