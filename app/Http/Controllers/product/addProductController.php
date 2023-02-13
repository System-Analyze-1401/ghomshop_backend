<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\FileSystem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class addProductController extends Controller
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
                'category_id' => 'required|integer|exists:categories,id',
                'name' => 'required|string|max:250',
                'image' => 'required|image|max:2048',
                'price' => 'required|integer|min:0',
                'status' => 'required|integer|in:' . implode(",", $status),
            ]
        );

        if ($validation->fails())
            return CryptIO::outputValidation(400, $validation->getMessageBag()->all()[0]);
    }

    protected function add(Request $request)
    {
        $validateFails = $this->validator($request);
        if ($validateFails)
            return $validateFails;

        $fileName = null;
        if ($request['image'])
            $fileName = FileSystem::upload($request['image']);

        $productId = Product::addProduct($request, $fileName);

        return CryptIO::encryptOutput(200, 'اطلاعات با موفقیت افزوده شد.', $productId);
    }
}