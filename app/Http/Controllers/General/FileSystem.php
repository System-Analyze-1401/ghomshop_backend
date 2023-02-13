<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use URL;

class FileSystem extends Controller
{
    public static function upload($file)
    {
        $fileName = md5(date('Y-m-d H:i:s') . rand(1000, 9999)) . '.' . $file->getClientOriginalExtension();

        $file->move('storage', $fileName);

        return $fileName;
    }

    public static function delete($name)
    {
        File::delete('storage/' . $name);
    }

    public static function urlGenerator($name)
    {
        return URL::to('storage/' . $name);
    }
}
