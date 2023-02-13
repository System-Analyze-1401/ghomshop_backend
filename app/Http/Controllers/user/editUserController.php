<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class editUserController extends Controller
{
    protected function validator($request)
    {
        $role = [
            User::ROLE_ADMIN,
            User::ROLE_USER,
        ];

        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:users',
                'first_name' => 'required|string|max:250',
                'last_name' => 'required|string|max:250',
                'address' => 'required|string',
                'phone_number' => 'required|iran_mobile',
                'email_address' => 'required|email',
                'password' => 'required|string|between:8,32',
                'role' => 'required|integer|in:' . implode(",", $role),
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function edit(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        User::editUser($request);

        return CryptIO::encryptOutput(200, 'اطلاعات با موفقیت ویرایش شد.');
    }
}