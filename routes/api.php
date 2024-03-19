<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function () {
    return response(['message' => 'This is a test.']);
});
Route::get('/test', function () {
    return response(['message' => 'This is a test.']);
});


// List APIs
Route::post('/POST/addlist', [ListController::class, "addList_post"]);
Route::post('/POST/deletelist', [ListController::class, "deleteList_post"]);
Route::post('/POST/updatelist', [ListController::class, "updateList_post"]);
Route::post('/POST/selectlist', [ListController::class, "selectList_post"]);
//

// Journey APIs
Route::post('/POST/addjourney', [JourneyController::class, "addJourney_post"]);
Route::post('/POST/deletejourney', [JourneyController::class, "deleteJourney_post"]);
Route::post('/POST/updatejourney', [JourneyController::class, "updateJourney_post"]);
Route::post('/POST/selectjourney', [JourneyController::class, "selectJourney_post"]);
Route::post('/POST/addjbudget', [JourneyController::class, "addJbudget_post"]);
Route::post('/POST/deletejbudget', [JourneyController::class, "deleteJbudget_post"]);
Route::post('/POST/updatejbudget', [JourneyController::class, "updateJbudget_post"]);
Route::post('/POST/selectjbudget', [JourneyController::class, "selectJbudget_post"]);
Route::post('/POST/addjimage', [JourneyController::class, "addJimage_post"]);
Route::post('/POST/deletejimage', [JourneyController::class, "deleteJimage_post"]);
Route::post('/POST/selectjimage', [JourneyController::class, "selectJimage_post"]);
//

// JourneyProject APIs
Route::post('/POST/addjourneyproject', [JourneyProjectController::class, "addJourneyProject_post"]);
Route::post('/POST/deletejourneyproject', [JourneyProjectController::class, "deleteJourneyProject_post"]);
Route::post('/POST/updatejourneyproject', [JourneyProjectController::class, "updateJourneyProject_post"]);
Route::post('/POST/selectjourneyproject', [JourneyProjectController::class, "selectJourneyProject_post"]);

Route::post('/POST/addjpbudget', [JourneyProjectController::class, "addJpbudget_post"]);
Route::post('/POST/deletejpbudget', [JourneyProjectController::class, "deleteJpbudget_post"]);
Route::post('/POST/updatejpbudget', [JourneyProjectController::class, "updateJpbudget_post"]);
Route::post('/POST/selectjpbudget', [JourneyProjectController::class, "selectJpbudget_post"]);

Route::post('/POST/addjpimage', [JourneyProjectController::class, "addJpimage_post"]);
Route::post('/POST/deletejpimage', [JourneyProjectController::class, "deleteJpimage_post"]);
Route::post('/POST/selectjpimage', [JourneyProjectController::class, "selectJpimage_post"]);
//

// Search APIs
Route::post('/POST/searchattraction', [SearchController::class, "selectAttraction_post"]);
Route::post('/POST/searchproject', [SearchController::class, "selectProject_post"]);


//


Route::post('/addcost', function (Request $request) {
    $title = $request->title;
    $cost = $request->cost;
    $tlid = DB::select('select tlid from touristlist where title = ?', [$title]);

    if (!empty ($tlid)) {
        $tlid = $tlid[0]->tlid;
        DB::insert('insert into listcost (cost,tlid) VALUES (?,?)', [$cost, $tlid]);
        echo "OK";
    } else {
        echo "list not found";
    }
});

// Showlist APIs
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Comment APIs
Route::resource("/comment", Comments::class);

// Special comment APIs
Route::get("/user-comment", [Comments::class, "no_id_given"]);
Route::get("/user-comment/{uid}", [Comments::class, "show_by_user"]);
Route::get("/project-comment", [Comments::class, "no_id_given"]);
Route::get("/project-comment/{pid}", [Comments::class, "show_by_pid"]);

// Attractions
Route::resource("/attraction", Attractions::class);
Route::get("/attraction-name/{aname}", [Attractions::class, "show_by_name"]);
// Route::get("/attraction-aname/{aname}", [Attractions::class, "show_by_name"]);

// Projects
Route::resource("/project", Projects::class);
Route::get("/project-name/{aname}", [Projects::class, "show_by_attraction"]);
// Route::get("/project-aname/{aname}", [Projects::class, "show_by_attraction"]);
