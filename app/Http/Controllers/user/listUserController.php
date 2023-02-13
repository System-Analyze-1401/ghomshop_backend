<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class listUserController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'search' => 'sometimes|nullable|string',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function index(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $list = User::listUser($request);

        return CryptIO::encryptOutput(200, null, $list);
    }
}