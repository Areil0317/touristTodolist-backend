<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JourneyModel;
use App\Models\AttractionModel;
use App\Models\ListModel;
use App\Models\JimageModel;
use App\Models\JbudgetModel;



class JourneyController extends Controller
{

    public function addJourney_post(Request $request)
    {

        $model = new JourneyModel;
        $tlid = $request->tlid;
        $list = ListModel::find($tlid);

        if (!$list) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        if($request->aname == null){
            $aname = "";
        }else{
            $aname = $request->aname;
        }


        $attraction = AttractionModel::firstOrCreate(['aname' => $aname]);

        $model->tlid = $tlid;
        $model->aid = $attraction->aid;

        $startDate = strtotime($list->start_date);
        $endDate = strtotime($list->end_date);

        if ($request->arrived_date == null) {
            $model->arrived_date = $list->start_date;
        } else {
            $arrivedDate = strtotime($request->arrived_date);
            if ($startDate <= $arrivedDate && $arrivedDate <= $endDate) {
                $model->arrived_date = $request->arrived_date;
            } else {
                $model->arrived_date = $list->start_date;
            }
        }

        if ($request->leaved_date == null) {
            $model->leaved_date = $model->arrived_date;
        } else {
            $leavedDate = strtotime($request->leaved_date);
            if ($startDate <= $leavedDate && $leavedDate <= $endDate) {
                $model->leaved_date = $request->leaved_date;
            } else {
                $model->leaved_date = $list->end_date;
            }
            
        }

        $model->arrived_time = $request->arrived_time;
        $model->leaved_time = $request->leaved_time;

        $arrivedDate = strtotime($model->arrived_date);
        $leavedDate = strtotime($model->leaved_date);

        if ($leavedDate <= $arrivedDate) {
            $model->leaved_date = $model->arrived_date;
            if (
                $model->leaved_time < $model->arrived_time
                && $model->leaved_time != null
                && $model->arrived_time != null
            ) {
                $model->leaved_time = $model->arrived_time;
            }
        }

        $model->jmemo = $request->jmemo;
        $model->jreview = $request->jreview;
        $model->jrate = $request->jrate;
        if(!isset($request->jchecked)){
            $model->jchecked = "0";
        }else{
            $model->jchecked = $request->jchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

    public function deleteJourney_post(Request $request)
    {

        $jid = $request->jid;
        $model = JourneyModel::find($jid);
        $model->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);
    }

    public function updateJourney_post(Request $request)
    {

        $jid = $request->jid;
        $model = JourneyModel::find($jid);

        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        if($request->aname == null){
            $aname = "請輸入行程名稱";
        }else{
            $aname = $request->aname;
        }

        $attraction = AttractionModel::firstOrCreate(['aname' => $aname]);
        $model->aid = $attraction->aid;

        $tlid = $model->tlid;
        $list = ListModel::find($tlid);

        $startDate = strtotime($list->start_date);
        $endDate = strtotime($list->end_date);

        //防止使用者把日曆input清空
        if ($request->arrived_date == null) {
            $model->arrived_date = $list->start_date;
        } else {
            $arrivedDate = strtotime($request->arrived_date);

            if ($startDate <= $arrivedDate && $arrivedDate <= $endDate) {
                $model->arrived_date = $request->arrived_date;
            } else {
                $model->arrived_date = $list->start_date;
            }
        }

        if ($request->leaved_date == null) {
            $model->leaved_date = $model->arrived_date;
        } else {
            $leavedDate = strtotime($request->leaved_date);
            if ($startDate <= $leavedDate && $leavedDate <= $endDate) {
                $model->leaved_date = $request->leaved_date;
            } else {
                $model->leaved_date = $list->end_date;
            }
        }

        $model->arrived_time = $request->arrived_time;
        $model->leaved_time = $request->leaved_time;

        $arrivedDate = strtotime($model->arrived_date);
        $leavedDate = strtotime($model->leaved_date);

        if ($leavedDate <= $arrivedDate) {
            $model->leaved_date = $model->arrived_date;
            if (
                $model->leaved_time < $model->arrived_time
                && $model->leaved_time != null
                && $model->arrived_time != null
            ) {
                $model->leaved_time = $model->arrived_time;
            }
        }

        $model->jmemo = $request->jmemo;
        $model->jreview = $request->jreview;
        $model->jrate = $request->jrate;
        if(!isset($request->jchecked)){
            $model->jchecked = "0";
        }else{
            $model->jchecked = $request->jchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data updated successfully'], 200);
    }

    public function selectJourney_post(Request $request)
    {
        $tlid = $request->tlid;
        $model = JourneyModel::where('tlid', $tlid)->get();
        
        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($model, 200);
    }


    public function addJbudget_post(Request $request)
    {

        if ($request->jbamount != null || $request->jbname != null) {

            $jid = $request->jid;
            $model = JourneyModel::find($jid);

            if (!$model) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jbudget = new JbudgetModel;
            $jbudget->jid = $model->jid;
            $jbudget->jbname = $request->jbname;
            $jbudget->jbamount = $request->jbamount;

            $jbudget->save();

            return response()->json(['message' => 'Data created successfully'], 200);


        } else {
            return response()->json(['message' => 'jbamount & jbname cannot both be null']);
        }

    }

    public function deleteJbudget_post(Request $request)
    {
        $jbid = $request->jbid;
        $jbudget = JbudgetModel::find($jbid);

        if (!$jbudget) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $jbudget->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);

    }

    public function updateJbudget_post(Request $request)
    {

        if ($request->jbamount != null || $request->jbname != null) {
            $jbid = $request->jbid;
            $jbudget = JbudgetModel::find($jbid);
            if (!$jbudget) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jbudget->jbname = $request->jbname;
            $jbudget->jbamount = $request->jbamount;

            $jbudget->save();
            return response()->json(['message' => 'Data updated successfully'], 200);


        } else {
            $jbid = $request->jbid;
            $jbudget = JbudgetModel::find($jbid);
            if (!$jbudget) {
                return response()->json(['message' => 'Data not found'], 404);
            }
            $jbudget->delete();
            return response()->json(['message' => 'jbamount deleted successfully'], 204);
        }
    }

    public function selectJbudget_post(Request $request)
    {
        $jbid = $request->jbid;
        $jbudget = JbudgetModel::find($jbid);
        if (!$jbudget) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($jbudget, 200);
    }


    public function addJimage_post(Request $request)
    {

        if ($request->jimg != null) {

            $jid = $request->jid;
            $model = JourneyModel::find($jid);

            if (!$model) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jimage = new JimageModel;
            $jimage->jid = $model->jid;
            $jimage->jimg = $request->jimg;


            $jimage->save();

            return response()->json(['message' => 'Data created successfully'], 200);


        } else {
            return response()->json(['message' => 'jimg cannot be null']);
        }

    }

    public function deleteJimage_post(Request $request)
    {
        $jiid = $request->jiid;
        $jimage = JimageModel::find($jiid);

        if (!$jimage) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $jimage->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);

    }

    public function selectJimage_post(Request $request)
    {
        $jiid = $request->jiid;
        $jimage = JimageModel::find($jiid);
        if (!$jimage) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($jimage, 200);

    }




}