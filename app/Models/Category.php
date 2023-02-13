<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public static function addCategory($request)
    {
        $category = new self();
        $category->name = $request['name'];
        $category->save();

        return $category->id;
    }

    public static function editCategory($request)
    {
        $category = self::find($request['id']);
        $category->name = $request['name'];
        $category->update();
    }

    public static function descriptionCategory($id)
    {
        return self::where('id', $id)->first();
    }

    public static function listCategory($request)
    {
        return self::when($request['search'], function ($query) use ($request) {
            return $query->where(
                function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%');
                }
            );
        })
            ->orderByDesc('id')
            ->get();
    }
}