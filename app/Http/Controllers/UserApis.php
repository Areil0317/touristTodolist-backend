<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApis extends Controller
{
    public function showlist(Request $request) {
        // $password = $request->password;
        $email = $request->email;
        $user = DB::select("select cost, listcost.tlid, title, users.id, name, email from touristlist left JOIN users ON touristlist.uid = users.id left JOIN listcost ON touristlist.tlid = listcost.tlid where email = ?", [$email]);
        return response($user)->header("Access-Control-Allow-Origin", "*");
    }
    public function showlist_get(Request $request) {
        // $password = $request->password;
        $email = $request->email;
        $user = DB::select("select cost, listcost.tlid, title, users.id, name, email from touristlist left JOIN users ON touristlist.uid = users.id left JOIN listcost ON touristlist.tlid = listcost.tlid where email = ?", [$email]);
        return response($user)->header("Access-Control-Allow-Origin", "*");
    }
}
