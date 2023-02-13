<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const STATUS_MOJOD = '1';
    public const STATUS_NAMOJOD = '2';

    public $timestamps = false;

    public static function addProduct($request, $fileName)
    {
        $product = new self();
        $product->category_id = $request['category_id'];
        $product->name = $request['name'];
        $product->image = $fileName;
        $product->price = $request['price'];
        $product->status = $request['status'];
        $product->save();

        return $product->id;
    }

    public static function editProduct($request, $fileName)
    {
        $product = self::find($request['id']);
        $product->category_id = $request['category_id'];
        $product->name = $request['name'];
        $product->image = $fileName;
        $product->price = $request['price'];
        $product->status = $request['status'];
        $product->update();
    }

    public static function descriptionProduct($id)
    {
        return self::where('id', $id)->first();
    }

    public static function listProduct($request)
    {
        return self::select(
            'products.*',
            'categories.id',
            'categories.name as category_name'
        )
            ->join('categories', 'categories.id', 'products.category_id')
            ->when($request['search'], function ($query) use ($request) {
                return $query->where(
                    function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request['search'] . '%');
                        }
                );
            })
            ->when($request['category_id'], function ($query) use ($request) {
                return $query->where(
                    function ($query) use ($request) {
                            $query->where('category_id', $request['categroy_id']);
                        }
                );
            })
            ->orderByDesc('products.id')
            ->get();
    }
}