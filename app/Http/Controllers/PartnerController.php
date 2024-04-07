<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BudgetManageModel;
use App\Models\PartnerModel;



class PartnerController extends Controller
{
    public function addPartner_post(Request $request)
    {

        $model = new PartnerModel;
        $bmid = $request->bmid;
        $budgetManage = BudgetManageModel::find($bmid);

        if (!$budgetManage) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $model->bmid = $bmid;
        $model->pnname = $request->pnname;
        $model->pnamount = $request->pnamount;
        
        if(!isset($request->pnchecked)){
            $model->pnchecked = "0";
        }else{
            $model->pnchecked = $request->pnchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

    public function deletePartner_post(Request $request)
    {
    
        $pnid = $request->pnid;
        $model = PartnerModel::find($pnid);
        $model->delete();
    
        return response()->json(['message' => 'Data deleted successfully'], 204);
    }


    public function updatePartner_post(Request $request)
    {

        $pnid = $request->pnid;
        $model = PartnerModel::find($pnid);

        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }


        $model->pnname = $request->pnname;
        $model->pnamount = $request->pnamount;
        
        if(!isset($request->pnchecked)){
            $model->pnchecked = "0";
        }else{
            $model->pnchecked = $request->pnchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }

}
