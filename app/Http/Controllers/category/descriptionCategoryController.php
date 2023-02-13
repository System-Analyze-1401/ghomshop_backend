<?php

namespace App\Http\Controllers\category;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class descriptionCategoryController extends Controller
{
    protected function validator($request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:categories',
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

        $result = Category::descriptionCategory($request['id']);

        return CryptIO::encryptOutput(200, null, $result);
    }
}