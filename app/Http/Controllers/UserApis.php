<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListModel;
use App\Models\JourneyModel;
use App\Models\JourneyProjectModel;



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
    public function showlist_get(Request $request) {
        // $password = $request->password;
        $email = $request->email;
        $user = DB::select("select cost, listcost.tlid, title, users.id, name, email from touristlist left JOIN users ON touristlist.uid = users.id left JOIN listcost ON touristlist.tlid = listcost.tlid where email = ?", [$email]);
        return response($user)->header("Access-Control-Allow-Origin", "*");
    }
}
