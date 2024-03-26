<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListModel;
use App\Models\JourneyModel;
use App\Models\JourneyProjectModel;
use App\Models\User;
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
        $userWithTouristLists = User::with('touristLists.journeys.attraction', 'touristLists.journeys.jbudgets','touristLists.journeys.jimages' ,'touristLists.journeys.journeyProjects.project','touristLists.journeys.journeyProjects.jpbudgets','touristLists.journeys.journeyProjects.jimages')
        ->find($user->id)->touristLists;
        // $touristLists = $userWithTouristLists->touristLists;
        // $result = $touristlists->map(function ($touristlist) {
        //     return [
        //         'tlid' => $touristlist->tlid,
        //         'id' => $touristlist->uid,
        //         'jid' => JourneyModel::where('tlid', $touristlist->tlid)->get()->map(function ($journey) {
        //             return ['jid' => $journey->jid];
        //         })
        //     ];
        // });
    
    

        return response()->json($userWithTouristLists);
    }
}
