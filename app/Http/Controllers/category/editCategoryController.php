<?php

namespace App\Http\Controllers\category;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class editCategoryController extends Controller
{
    protected function validator($request)
    {

        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:categories',
                'name' => 'required|string|max:250',
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

        Category::editCategory($request);

        return CryptIO::encryptOutput(200, 'اطلاعات با موفقیت ویرایش شد.');
    }
}