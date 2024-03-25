<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListModel;
use App\Models\JourneyModel;
use App\Models\JourneyProjectModel;
use Illuminate\Support\Facades\Auth;



class UserApis extends Controller
{
    public function userRelatedIds(Request $request) {
        $uid = $request->id;

        $tlid = ListModel::where('uid', $uid)->pluck('tlid')->toArray();
        $jid = JourneyModel::whereIn('tlid', $tlid)->pluck('jid')->toArray();
        $jpid = JourneyProjectModel::whereIn('jid', $jid)->pluck('jpid')->toArray();
        
        $data = [];
        if ($tlid !== null) {
            $data['tlid'] = $tlid;
        }
        if ($jid !== null) {
            $data['jid'] = $jid;
        }
        if ($jpid !== null) {
            $data['jpid'] = $jpid;
        }
    
        return response()->json($data);

    }
    public function userAllInformation_get(Request $request) {
        
        $user = Auth::user();

        $results = DB::table('touristlist')
    ->leftJoin('journey', 'touristlist.tlid', '=', 'journey.tlid')
    ->leftJoin('attractions', 'journey.aid', '=', 'attractions.aid')
    ->leftJoin('jbudget', 'journey.jid', '=', 'jbudget.jid')
    ->leftJoin('jimage', 'journey.jid', '=', 'jimage.jid')
    ->leftJoin('journeyproject', 'journey.jid', '=', 'journeyproject.jid')
    ->leftJoin('project', 'journeyproject.pid', '=', 'project.pid')
    ->leftJoin('jpbudget', 'journeyproject.jpid', '=', 'jpbudget.jpbid')
    ->leftJoin('jpimage', 'journeyproject.jpid', '=', 'jpimage.jpid')
    ->where('touristlist.uid', '=', $user->id)
    ->select('*')
    ->get();

        return response()->json($results);
    }
}
