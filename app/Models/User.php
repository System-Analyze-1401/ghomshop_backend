<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public const ROLE_ADMIN = '1';
    public const ROLE_USER = '2';

    public $timestamps = false;

    public static function addUser($request)
    {
        $user = new self();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->address = $request['address'];
        $user->phone_number = $request['phone_number'];
        $user->email_address = $request['email_address'];
        $user->password = $request['password'];
        $user->role = $request['role'];
        $user->save();

        return $user->id;
    }
    public static function register($request)
    {
        $user = new self();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->address = $request['address'];
        $user->phone_number = $request['phone_number'];
        $user->email_address = $request['email_address'];
        $user->password = $request['password'];
        $user->role = self::ROLE_USER;
        $user->save();

        return $user->id;
    }

    public static function addRefreshToken($userId, $refreshToken)
    {
        $user = self::find($userId);
        $user->refresh_token = $refreshToken;
        $user->update();
    }

    public static function editUser($request)
    {
        $user = self::find($request['id']);
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->address = $request['address'];
        $user->phone_number = $request['phone_number'];
        $user->email_address = $request['email_address'];
        $user->password = $request['password'];
        $user->role = $request['role'];
        $user->update();
    }

    public static function descriptionUser($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getUserIdWithEmailAddress($emailAddress)
    {
        return self::where('email_address', $emailAddress)->first()->id;
    }

    public static function getUserIdByUserRefreshToken($refreshToken)
    {
        return self::where('refresh_token', $refreshToken)->first()->id;
    }

    public static function listUser($request)
    {
        return self::when($request['search'], function ($query) use ($request) {
            return $query->where(
                function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request['search'] . '%')
                        ->orWhere('last_name', 'like', '%' . $request['search'] . '%')
                        ->orWhere('phone_number', 'like', '%' . $request['search'] . '%')
                        ->orWhere('email_address', 'like', '%' . $request['search'] . '%');
                }
            );
        })
            ->orderByDesc('id')
            ->get();
    }
}