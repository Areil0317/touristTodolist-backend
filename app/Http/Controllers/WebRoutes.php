<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebRoutes extends Controller
{
    public function index() {
        return response([ "message" => "Hello World" ]);
    }
    public function avatars($filename) {
        $path = storage_path("app/public/avatars/$filename");
        if (!Storage::exists("public/avatars/$filename")) {
            abort(404);
        }
        return response()->file($path);
    }
}
