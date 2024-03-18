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

        if ($request->jpstart_date == null) {
            $model->jpstart_date = $journey->arrived_date;
        } else {
            $arrivedDate = strtotime($journey->arrived_date);
            $jpStartDate = strtotime($request->jpstart_date);
            $leavedDate = strtotime($journey->leaved_date);

            if ($arrivedDate <= $jpStartDate && $jpStartDate <= $leavedDate) {
                $model->jpstart_date = $request->jpstart_date;
            } else {
                $model->jpstart_date = $journey->arrived_date;
            }
        }

        if ($request->jpend_date == null) {
            $model->jpend_date = $model->jpstart_date;
        } else {
            $model->jpend_date = $request->jpend_date;
        }

        $model->jpstart_time = $request->jpstart_time;
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

        $model->jpmemo = $request->jpmemo;
        $model->jpreview = $request->jpreview;
        $model->jprate = $request->jprate;
        $model->jpchecked = $request->jpchecked;

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

}
