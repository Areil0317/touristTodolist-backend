<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/", function () {
    return response([ "message" => "Hello World" ]);
});

Route::get("/avatars/{filename}", function ($filename) {
    $path = storage_path("app/public/avatars/$filename");
    if (!Storage::exists("public/avatars/$filename")) {
        abort(400);
    }
    return response()->file($path);
})->where("filename", ".*");
