<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\FileSystem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class editOrderController extends Controller
{
    protected function validator($request)
    {
        $status = [
            Order::STATUS_PENDING,
            Order::STATUS_PAID,
            Order::STATUS_DONE,
            Order::STATUS_CANCELED,
        ];

        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:orders',
                'status' => 'required|integer|in:' . implode(",", $status),
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

        Order::editOrder($request);

        return CryptIO::encryptOutput(200, 'اطلاعات با موفقیت ویرایش شد.');
    }

    protected function validatorVerify($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:orders',
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function verify(Request $request)
    {
        $validateFails = $this->validatorVerify($request);
        if ($validateFails)
            return $validateFails;

        $orderDesc = Order::descriptionOrder($request['id']);

        if ($orderDesc['status'] != Order::STATUS_PENDING)
            return CryptIO::encryptOutput(400, 'این سفارش قبلا پرداخت با کنسل شذه است.');

        Order::verify($request['id']);

        return CryptIO::encryptOutput(200, 'سفارش شما پرداخت نهایی شده و توسط پشتیبان بررسی خواهد شد.');
    }

    protected function cancel(Request $request)
    {
        $validateFails = $this->validatorVerify($request);
        if ($validateFails)
            return $validateFails;

        $orderDesc = Order::descriptionOrder($request['id']);

        if ($orderDesc['status'] == Order::STATUS_CANCELED)
            return CryptIO::encryptOutput(400, 'این سفارش قبلا کنسل شده است.');

        if ($orderDesc['status'] != Order::STATUS_PENDING)
            return CryptIO::encryptOutput(400, 'این سفارش قبلا پرداخت با تایید شده است و امکان کنسلی وجود ندارد.');

        Order::cancel($request['id']);

        return CryptIO::encryptOutput(200, 'سفارش شما کنسل شد.');
    }
}