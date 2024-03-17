<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListModel;


class ListController extends Controller
{
    public function addList_post(Request $request)
    {

        $model = new ListModel;
        $model->uid = $request->uid;
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

        if ($model->end_date < $model->start_date) {
            $model->end_date = $model->start_date;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

    public function deleteList_post(Request $request)
    {

        $tlid = $request->tlid;
        $model = ListModel::find($tlid);
        $model->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);
    }

    public function updateList_post(Request $request)
    {

        $tlid = $request->tlid;
        $model = ListModel::find($tlid);

        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
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

        if ($model->end_date < $model->start_date) {
            $model->end_date = $model->start_date;
        }

        $model->save();
        return response()->json(['message' => 'Data updated successfully'], 200);
    }

    public function selectList_post(Request $request)
    {

        $tlid = $request->tlid;
        $model = ListModel::find($tlid);

        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($model, 200);
    }
}
