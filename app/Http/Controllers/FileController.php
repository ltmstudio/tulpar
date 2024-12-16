<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function getFile($foldername, $user_folder, $filename)
    {
        return response()->json(['message' => 'Unauthorized'], 403);
        $fullpath = "{$foldername}/{$user_folder}/{$filename}";
        return response()->download(storage_path($fullpath), null, [], null);
    }
}
