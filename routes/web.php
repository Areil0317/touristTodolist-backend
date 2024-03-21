<?php
namespace App\Http\Controllers;

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

Route::get("/", [WebRoutes::class, "index"]);

// http://127.0.0.1:8000/storage/avatars/BolaBzjE3xZatSvVd1swgmv4Dc8n6rgFwfGaPYVr.svg
Route::get("/storage/avatars/{filename}", [WebRoutes::class, "avatars"])->where("filename", ".*");
Route::get("/avatars/{filename}", [WebRoutes::class, "avatars"])->where("filename", ".*");
