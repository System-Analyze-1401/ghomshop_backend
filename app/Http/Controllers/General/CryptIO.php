<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CryptIO extends Controller
{
    public static function decryptInput($input)
    {
        return base64_decode($input);
    }

    public static function unauthorized()
    {
        return Response::json([
            'code' => 401,
            'description' => "دسترسی شما منقضی شده . لطفا دوباره وارد شوید",
            'data' => null,
        ], 401);
    }

    public static function accessDenied()
    {
        return Response::json([
            'code' => 403,
            'description' => "به این سرویس دسترسی ندارید",
            'data' => null,
        ], 403);
    }

    public static function output($code, $messageKey, $data = null)
    {
        return Response::json([
            'code' => $code,
            'description' => __('message.' . $messageKey),
            'data' => $data,
        ], 200);
    }

    public static function outputValidation($code, $description, $data = null)
    {
        return Response::json([
            'code' => $code,
            'description' => $description,
            'data' => $data,
        ], 200);
    }

    public static function encryptOutput($code, $description, $data = null)
    {
        //todo delete codes
//        $json = json_encode([
//            'code' => $code,
//            'description' => $description,
//            'data' => $data,
//        ], JSON_UNESCAPED_UNICODE);
//
//        return base64_encode($json);
//
//        $json = json_decode($json);
//        return response()->json($json);

        return Response::json([
            'code' => $code,
            'description' => $description,
            'data' => $data,
        ], 200);
    }

    public static function isJson($input)
    {
        json_decode($input);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}