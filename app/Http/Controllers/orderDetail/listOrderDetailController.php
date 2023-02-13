<?php

namespace App\Http\Controllers\orderDetail;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class listOrderDetailController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'order_id' => 'required|integer|exists:orders,id',
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

        $list = OrderDetail::listOrderDetailWithOrderId($request['order_id']);

        return CryptIO::encryptOutput(200, null, $list);
    }
}