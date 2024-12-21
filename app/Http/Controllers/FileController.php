<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function getFile()
    {
        $path = request()->query('path');
        if (!$path) {
            return response()->json(['message' => 'Path query parameter is required'], 400);
        }
        $path = 'app/' . $path;
        if (!file_exists(storage_path($path))) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return response()->download(storage_path($path), null, [], null);
    }
}
