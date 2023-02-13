<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\Authentication;
use App\Http\Controllers\General\CryptIO;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class addOrderController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'products' => 'required|array',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function addDraft(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $userId = Authentication::getUserIdByToken($request->bearerToken());

        $orderId = Order::addDraft($userId);

        for ($i = 0; $i < count($request['products']); $i++) {
            OrderDetail::add($request['products'][$i], $orderId);
        }

        return CryptIO::encryptOutput(200, 'سفارش شما ثبت گردید و آماده پرداخت می باشد.', $orderId);
    }
}