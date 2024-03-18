<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JourneyModel;
use App\Models\AttractionModel;
use App\Models\JourneyProjectModel;
use App\Models\ProjectModel;
use App\Models\JpbudgetModel;
use App\Models\JpimageModel;

class JourneyProjectController extends Controller
{
    public function addJourneyProject_post(Request $request)
    {

        $model = new JourneyProjectModel;
        $jid = $request->jid;
        $journey = JourneyModel::find($jid);

        if (!$journey) {
            return response()->json(['message' => 'Data not found'], 404);
        }


        $aid = $journey->aid;

        $pname = $request->pname;
        $project = ProjectModel::firstOrCreate(['pname' => $pname, 'aid' => $aid]);

        $model->jid = $journey->jid;
        $model->pid = $project->pid;
        $arrivedDate = strtotime($journey->arrived_date);
        $leavedDate = strtotime($journey->leaved_date);
        $jpStartDate = strtotime($request->jpstart_date);
        $jpEndDate = strtotime($request->jpend_date);



        if ($request->jpstart_date == null) {
            $model->jpstart_date = $journey->arrived_date;
        } else {

            if ($arrivedDate <= $jpStartDate && $jpStartDate <= $leavedDate) {
                $model->jpstart_date = $request->jpstart_date;
            } else {
                $model->jpstart_date = $journey->arrived_date;
            }
        }

        if ($request->jpend_date == null) {
            $model->jpend_date = $model->jpstart_date;
        } else {
            if ($arrivedDate <= $jpEndDate && $jpEndDate <= $leavedDate) {
                $model->jpend_date = $request->jpend_date;
            } else {
                $model->jpend_date = $journey->leaved_date;
            }
        }

        $arrivedTime = strtotime($journey->arrived_time);
        $leavedTime = strtotime($journey->leaved_time);
        $jpStartTime = strtotime($request->jpstart_time);
        $jpEndTime = strtotime($request->jpend_time);


        // 有設置地點起始與活動起始
        if (isset ($arrivedTime) && isset ($jpStartTime)) {
            // (O)地點起始比活動起始早
            if ($arrivedTime <= $jpStartTime) {
                // (X)有設置地點終止並且地點終止比活動起始早
                if (isset ($leavedTime) && $leavedTime < $jpStartTime) {
                    if ($leavedDate != $jpStartDate) {
                        $model->jpstart_time = $request->jpstart_time;
                    } else {
                        $model->jpstart_time = $journey->leaved_time;
                    }
                    // 設定活動起始時間為地點終止時間
                    // (O)沒有設置地點終止或是地點終止比活動起始晚
                } else {
                    // 設定活動起始時間為填入的活動起始時間
                    $model->jpstart_time = $request->jpstart_time;
                }
                // (X)地點起始比活動起始晚    
            } else {
                // 設定活動起始時間為地點起始時間
                if ($arrivedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->arrived_time;
                }
            }
            // 沒有設置地點起始或沒有設置活動起始或兩者都沒有設置
        } else {
            // (X)有設置地點終止與活動起始並且地點終止比活動起始早
            if (isset ($leavedTime) && isset ($jpStartTime) && $leavedTime < $jpStartTime) {
                // 設置活動起始為地點終止
                if ($leavedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->leaved_time;
                }
                // (O)有設置地點終止與活動起始並且地點終止比活動起始晚
            } else {
                // 設定活動起始時間為填入的活動起始時間
                $model->jpstart_time = $request->jpstart_time;
            }
        }


        // 有設置地點結束與活動結束
        if (isset ($leavedTime) && isset ($jpEndTime )) {
            // (O)活動結束比地點結束早
            if ($leavedTime >= $jpEndTime) {
                // (X)有設置地點開始並且地點開始比活動結束早
                if (isset ($arrivedTime ) && $arrivedTime > $jpEndTime) {
                    if ($arrivedDate != $jpEndDate) {
                        $model->jpend_time = $request->jpend_time;
                    } else {
                        $model->jpstart_time = $journey->leaved_time;
                    }
                    // 設定活動起始時間為地點終止時間
                    // (O)沒有設置地點終止或是地點終止比活動起始晚
                } else {
                    // 設定活動起始時間為填入的活動起始時間
                    $model->jpstart_time = $request->jpstart_time;
                }
                // (X)地點起始比活動起始晚    
            } else {
                // 設定活動起始時間為地點起始時間
                if ($arrivedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->arrived_time;
                }
            }
            // 沒有設置地點起始或沒有設置活動起始或兩者都沒有設置
        } else {
            // (X)有設置地點終止與活動起始並且地點終止比活動起始早
            if (isset ($leavedTime) && isset ($jpStartTime) && $leavedTime < $jpStartTime) {
                // 設置活動起始為地點終止
                if ($leavedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->leaved_time;
                }
                // (O)有設置地點終止與活動起始並且地點終止比活動起始晚
            } else {
                // 設定活動起始時間為填入的活動起始時間
                $model->jpstart_time = $request->jpstart_time;
            }
        }





        $model->jpend_time = $request->jpend_time;



        $jpStartDate = strtotime($model->jpstart_date);
        $jpEndDate = strtotime($model->jpend_date);

        if ($jpEndDate <= $jpStartDate) {
            $model->jpend_date = $model->jpstart_date;
            if (
                $model->jpend_time < $model->jpstart_time
                && $model->jpend_time != null
                && $model->jpstart_time != null
            ) {
                $model->jpend_time = $model->jpstart_time;
            }
        }

        // if(isset($journey->leaved_time))


        $model->jpmemo = $request->jpmemo;
        $model->jpreview = $request->jpreview;
        $model->jprate = $request->jprate;

        if (!isset ($request->jpchecked)) {
            $model->jpchecked = "0";
        } else {
            $model->jpchecked = $request->jpchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

}
