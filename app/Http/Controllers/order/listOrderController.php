<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\Authentication;
use App\Http\Controllers\General\CryptIO;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class listOrderController extends Controller
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

        $list = Order::listOrder($request);

        return CryptIO::encryptOutput(200, null, $list);
    }

    protected function myList(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $userId = Authentication::getUserIdByToken($request->bearerToken());

        $list = Order::listUserOrder($request, $userId);

        for ($i = 0; $i < count($list); $i++) {
            $list[$i]['order_detail'] = OrderDetail::listOrderDetailWithOrderId($list[$i]['id']);
        }

        return CryptIO::encryptOutput(200, null, $list);
    }
}