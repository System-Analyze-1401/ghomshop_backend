<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\FileSystem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class editProductController extends Controller
{
    protected function validator($request)
    {
        $status = [
            Product::STATUS_MOJOD,
            Product::STATUS_NAMOJOD,
        ];
        $validation = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:products',
                'category_id' => 'required|integer|exists:categories,id',
                'name' => 'required|string|max:250',
                'image' => 'sometimes|nullable|image|max:2048',
                'price' => 'required|integer|min:0',
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

        $fileName = null;
        if ($request['image'])
            $fileName = FileSystem::upload($request['image']);

        Product::editProduct($request, $fileName);

        return CryptIO::encryptOutput(200, 'اطلاعات با موفقیت ویرایش شد.');
    }
}