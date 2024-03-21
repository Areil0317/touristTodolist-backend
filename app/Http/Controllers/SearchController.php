<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AttractionModel;
use App\Models\ProjectModel;

class SearchController extends Controller
{
    public function selectSimilarAttraction_post(Request $request)
    {
        $aname = $request->aname;
        $model = AttractionModel::where('aname', 'like', '%' . $aname . '%')->get();
        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($model, 200);
    }

    public function selectAttraction_post(Request $request)
    {
        $aid = $request->aid;
        $model = AttractionModel::find($aid);
        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($model, 200);
    }

    public function selectProject_post(Request $request)
    {
        $aid = $request->aid;
        $pidList = ProjectModel::where('aid', $aid)->pluck('pid');


        if (!$pidList) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $counts = DB::table('journeyproject')
            ->whereIn('pid', $pidList)
            ->select('pid', DB::raw('count(*) as count'))
            ->groupBy('pid')
            ->get();

        $result = [];
        foreach ($pidList as $pid) {
            $project = ProjectModel::find($pid);
            $count = $counts->where('pid', $pid)->first()->count ?? 0;
            $project->count = $count;
            $result[] = $project;
        }
        ;

        usort($result, function($a, $b) {
            return $b->count <=> $a->count;
        });
    
        return response()->json($result, 200);
    }

}
