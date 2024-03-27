<?php

namespace App\Http\Controllers;

use App\Models\JourneyProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListModel;
use Illuminate\Support\Facades\Auth;
use App\Models\JourneyModel;
use App\Models\AttractionModel;


class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except([
            // "selectList_post"
        ]);
    }
    /**
     * You need to attactch title, the title of the list:
     *
     * {"name":"Example list"}
     */
    public function addList_post(Request $request)
    {
        // 從請求中取得當前驗證的用戶
        $user = Auth::user();
        if( !isset($user) ) {
            return response([
                "message" => "No such user",
                "user" => $user
            ], 401);
        }

        // Create a new ListModel instance
        $model = new ListModel;
        $model->uid = $user->id;
        $model->title = $request->title;

        // Set dates
        $model->start_date = $request->start_date ?? $model->freshTimestamp();
        $model->end_date = $request->end_date ?? $model->start_date;

        // Ensure end date is not before start date
        if (strtotime($model->end_date) < strtotime($model->start_date)) {
            $model->end_date = $model->start_date;
        }

        $model->save();

        
        // $journey = new JourneyModel;
        // $journey->tlid = $model->tlid;
        // $aname = "輸入您的第一個行程";
        // $attraction = AttractionModel::firstOrCreate(['aname' => $aname]);

        // $journey->aid = $attraction->aid;
// $journey->save();

        return response()->json([
            "message" => "Data created successfully",
            "result" => $model,
            // "result2" => $journey,
        ], 201);
    }

    public function deleteList_post(Request $request)
    {
        $user = Auth::user();
        $tlid = $request->tlid;
        $model = ListModel::find($tlid);

        if( !isset($user) ) {
            return response([
                "message" => "No such user",
                "user" => $user
            ], 401);
        }
        if( $model->uid != $user->id ) {
            return response([
                "message" => "Unauthorised user",
                "user" => $user->id
            ], 401);
        }
        $model->delete();

        return response([
            'message' => 'Data deleted successfully'
        ], 200);
    }

    public function updateList_post(Request $request)
    {
        $user = Auth::user();
        $tlid = $request->tlid;
        $model = ListModel::find($tlid);

        if( !isset($user) ) {
            return response([
                "message" => "No such user",
                "user" => $user
            ], 401);
        }
        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }
        if( $model->uid != $user->id ) {
            return response([
                "message" => "Unauthorised user",
                "user" => $user->id
            ], 401);
        }

        $model->title = $request->title;

        if ($request->start_date == null) {
            $model->start_date = $model->freshTimestamp();
        } else {
            $model->start_date = $request->start_date;
        }

        if ($request->end_date == null) {
            $model->end_date = $model->start_date;
        } else {
            $model->end_date = $request->end_date;
        }

        $startDate = strtotime($model->start_date);
        $endDate = strtotime($model->end_date);
        if ($endDate < $startDate) {
            $model->end_date = $model->start_date;
        }

        $model->save();
        return response()->json(['message' => 'Data updated successfully'], 200);
    }

    public function selectList_post(Request $request)
    {
        $user = Auth::user();
        $touristlists = ListModel::where('uid', $user->id)->get();
        if (!$touristlists) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($touristlists->toArray(), 200);
    }

    public function getTouristListTitles(Request $request)
    {
        // 從請求中取得當前驗證的用戶
        $user = Auth::user();

        // 根據用戶id查找touristlist中的標題
        $titles = ListModel::where('uid', $user->id)->pluck('title');

        // 返回查詢結果
        return response()->json(['titles' => $titles]);
    }

    public function getUserTourList(Request $request)
    {
        $user = $request->user(); // 通過Sanctum獲取當前認證的用戶

        $tourLists = ListModel::where('uid', $user->id)->get();

        $result = [];
        foreach ($tourLists as $tourList) {
            // 為每個tourList項目查詢相應的Journeys
            $journeys = JourneyModel::where('tlid', $tourList->tlid)->get();

            $journeyDetails = [];
            foreach ($journeys as $journey) {
                // 為每個Journey查詢相應的Attractions
                $attractions = AttractionModel::where('aid', $journey->aid)->pluck('aname')->toArray();

                // 查詢與當前Journey相關的所有JourneyProject
                $projects = JourneyProjectModel::where('jid', $journey->jid)
                    ->join('project', 'journeyproject.pid', '=', 'project.pid')
                    ->pluck('project.pname')->unique();

                // 將景點和項目信息加入到每個Journey的詳細信息中
                $journeyDetails[] = [
                    'journeyId' => $journey->jid,
                    'attractions' => $attractions,
                    'projects' => $projects
                ];
            }

            // 將每個tourList及其對應的Journeys加入結果數據中
            $result[] = [
                'tourListTitle' => $tourList->title,
                'journeys' => $journeyDetails
            ];
        }

        return response()->json($result);
    }
}
