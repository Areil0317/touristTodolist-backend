<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Comments;
use App\Http\Controllers\UserApis;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function() {
    return response(['message' => 'This is a test.']);
});
Route::get('/test', function() {
    return response(['message' => 'This is a test.']);
});

// Add APIs
Route::post('/add', function (Request $request) {
    $title = $request->title;
    $email = $request->email;
    $uid = DB::select('select uid from users where email = ?' , [$email]);

    if (!empty($uid)) {
        $uid = $uid[0]->uid;
        DB::insert('insert into touristlist (title,uid) VALUES (?,?)', [$title, $uid]);
        echo "OK";
    } else {
        echo "User not found";
    }
});

Route::post('/addcost', function (Request $request) {
    $title = $request->title;
    $cost = $request->cost;
    $tlid = DB::select('select tlid from touristlist where title = ?' , [$title]);

    if (!empty($tlid)) {
        $tlid = $tlid[0]->tlid;
        DB::insert('insert into listcost (cost,tlid) VALUES (?,?)', [$cost, $tlid]);
        echo "OK";
    } else {
        echo "list not found";
    }
});

Route::get("/showlist/{email}", [UserApis::class, "showlist_get"]);
Route::post("/showlist", [UserApis::class, "showlist"]);

Route::post('/update', function (Request $request) {

    $name = $request->name;
    $password = $request->password;
    $email = $request->email;

    DB::update("update users set password = ? where email = ?", [$password, $email]);
    DB::update("update users set name = ? where email = ?", [$name, $email]);

    echo "更改成功";

});

// User APIs
Route::get('/get', function (Request $request) {
    $user = DB::select("select * from users");
    return response($user)->header("Access-Control-Allow-Origin", "*");
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Comment APIs
Route::resource("/comment", Comments::class);
// Special comment APIs
Route::get("/user-comment", [Comments::class, "no_id_given"]);
Route::get("/thread-comment", [Comments::class, "no_id_given"]);
Route::get("/user-comment/{uid}", [Comments::class, "show_by_user"]);
Route::get("/thread-comment/{tid}", [Comments::class, "show_by_thread"]);
