<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class descriptionOrderController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:orders',
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

        $result = Order::descriptionOrder($request['id']);

        return CryptIO::encryptOutput(200, null, $result);
    }
}