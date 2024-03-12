<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyCont extends Controller
{
    public function main() {
        return response([
            "message" => "Nothing here. Just go away."
        ], 418);
    }
    public function show_by_user($uid) {
        $sql = DB::select("SELECT tid as thread, comment, rate, date FROM `comments` WHERE uid = ? ORDER BY date ASC", [$uid]);
        return [
            "uid" => $uid,
            "result" => $sql
        ];
    }
    public function show_by_thread($tid) {
        $sql = DB::select("SELECT uid as user, comment, rate, date FROM `comments` WHERE tid = ? ORDER BY date ASC", [$tid]);
        return [
            "tid" => $tid,
            "result" => $sql
        ];
    }

}
