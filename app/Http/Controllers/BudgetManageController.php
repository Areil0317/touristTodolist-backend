<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BudgetManageModel;
use App\Models\ListModel;


class BudgetManageController extends Controller
{
    public function addBudgetManage_post(Request $request)
    {

        $model = new BudgetManageModel;
        $tlid = $request->tlid;
        $list = ListModel::find($tlid);

        if (!$list) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $model->tlid = $tlid;
        $model->bmname = $request->bmname;
        $model->bmamount = $request->bmamount;
        
        if(!isset($request->bmchecked)){
            $model->bmchecked = "0";
        }else{
            $model->bmchecked = $request->bmchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

    public function deleteBudgetManage_post(Request $request)
    {
    
        $bmid = $request->bmid;
        $model = BudgetManageModel::find($bmid);
        $model->delete();
    
        return response()->json(['message' => 'Data deleted successfully'], 204);
    }
    

public function updateBudgetManage_post(Request $request)
    {

        $bmid = $request->bmid;
        $model = BudgetManageModel::find($bmid);

        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $model->bmname = $request->bmname;
        $model->bmamount = $request->bmamount;
        
        if(!isset($request->bmchecked)){
            $model->bmchecked = "0";
        }else{
            $model->bmchecked = $request->bmchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

}

