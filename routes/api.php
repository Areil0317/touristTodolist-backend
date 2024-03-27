<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Hash;

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

Route::get('/', function () {
    return response([
        'message' => 'Welcome to the project API!'
    ], 200);
});
Route::get('/test', function () {
    return response([
        'message' => 'You called the API successfully.'
    ], 200);
});

// User All list APIs userAllInformation_get
Route::middleware('auth:sanctum')->get('/user_all_informations', [UserApis::class, "userAllInformation_get"]);

// List APIs
Route::post('/POST/addlist', [ListController::class, "addList_post"]);
Route::post('/POST/deletelist', [ListController::class, "deleteList_post"]);
Route::post('/POST/updatelist', [ListController::class, "updateList_post"]);
Route::post('/POST/selectlist', [ListController::class, "selectList_post"]);

// Journey APIs
Route::post('/POST/addjourney', [JourneyController::class, "addJourney_post"]);
Route::post('/POST/deletejourney', [JourneyController::class, "deleteJourney_post"]);
Route::post('/POST/updatejourney', [JourneyController::class, "updateJourney_post"]);
Route::post('/POST/selectjourney', [JourneyController::class, "selectJourney_post"]);

// Image APIs
Route::post('/POST/addjimage', [JourneyController::class, "addJimage_post"]);
Route::post('/POST/deletejimage', [JourneyController::class, "deleteJimage_post"]);
Route::post('/POST/selectjimage', [JourneyController::class, "selectJimage_post"]);
Route::middleware('auth:sanctum')->post('/images/upload', [ImagesController::class, "add_jimage"]);
Route::middleware('auth:sanctum')->post('/jpimages/upload', [ImagesController::class, "add_jpimage"]);

// JourneyProject APIs
Route::post('/POST/addjourneyproject', [JourneyProjectController::class, "addJourneyProject_post"]);
Route::post('/POST/deletejourneyproject', [JourneyProjectController::class, "deleteJourneyProject_post"]);
Route::post('/POST/updatejourneyproject', [JourneyProjectController::class, "updateJourneyProject_post"]);
Route::post('/POST/selectjourneyproject', [JourneyProjectController::class, "selectJourneyProject_post"]);

// JourneyBudget APIs
Route::post('/POST/addjbudget', [JourneyController::class, "addJbudget_post"]);
Route::post('/POST/deletejbudget', [JourneyController::class, "deleteJbudget_post"]);
Route::post('/POST/updatejbudget', [JourneyController::class, "updateJbudget_post"]);
Route::post('/POST/selectjbudget', [JourneyController::class, "selectJbudget_post"]);

// JourneyProjectBudget APIs
Route::post('/POST/addjpbudget', [JourneyProjectController::class, "addJpbudget_post"]);
Route::post('/POST/deletejpbudget', [JourneyProjectController::class, "deleteJpbudget_post"]);
Route::post('/POST/updatejpbudget', [JourneyProjectController::class, "updateJpbudget_post"]);
Route::post('/POST/selectjpbudget', [JourneyProjectController::class, "selectJpbudget_post"]);

// Image APIs
Route::post('/POST/addjpimage', [JourneyProjectController::class, "addJpimage_post"]);
Route::post('/POST/deletejpimage', [JourneyProjectController::class, "deleteJpimage_post"]);
Route::post('/POST/selectjpimage', [JourneyProjectController::class, "selectJpimage_post"]);


// Search APIs
Route::post('/POST/searchsimilarattraction', [SearchController::class, "selectSimilarAttraction_post"]);
Route::post('/POST/searchproject', [SearchController::class, "selectProject_post"]);
Route::post('/POST/searchattraction', [SearchController::class, "selectAttraction_post"]);
Route::post('/POST/searchprojectname', [SearchController::class, "selectProjectName_post"]);

// Showlist APIs
Route::post("/POST/userrelatedids", [UserApis::class, "userRelatedIds"]);

// User authorisation APIs
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// User updating APIs
Route::post('/update-avatar', [UserController::class, 'updateAvatar'])->name('profile.update-avatar');
Route::put('/update', [UserController::class, 'update']);
Route::put('/updatePassword', [UserController::class, 'updatePassword']);
Route::put('/update-password', [UserController::class, 'updatePassword']);

//Attribution APIs
Route::get('/touristlist-title', [ListController::class, 'getTouristListTitles']);
Route::get('/user-tourlist', [ListController::class, 'getUserTourList']);
Route::get('/user-score', [UserController::class, 'calculateScore']);
Route::get("/uploaded-images", [ImagesController::class, "list_by_token"]);
Route::get("/uploaded-images/{uid}", [ImagesController::class, "list_by_uid"]);

// Comment APIs
Route::resource("/comment", Comments::class);
Route::get("/comment/{cid}/changelog", [CommentsBySpecialCall::class, "show_comment_changelog"]);
Route::get("/user-comment", [CommentsBySpecialCall::class, "show_by_user_by_token"]);
Route::get("/user-comment/{uid}", [CommentsBySpecialCall::class, "show_by_user"]);

// Attraction APIs
Route::resource("/attraction", Attractions::class);
Route::get("/attraction-name/{aname}", [Attractions::class, "show_by_name"]);

// Project APIs
Route::resource("/project", Projects::class);
Route::get("/project/{pid}/comments", [CommentsBySpecialCall::class, "show_by_pid"]);
Route::get("/project-name/{aname}", [Projects::class, "show_by_attraction"]);

// Other APIs
