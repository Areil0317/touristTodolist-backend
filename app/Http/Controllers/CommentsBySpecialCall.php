<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentsBySpecialCall extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->only([
            "show_by_user_by_token",
        ]);
    }

    public function index()
    {
        return response([
            "message" => "Please provide ID for querying.",
            "result" => []
        ], 400);
    }

    /**
     * Get the user's comments by given $uid.
     */
    public function show_by_user($uid) {
        $model = new CommentModel();
        $sql = $model->find_by_user($uid);
        return [
            "uid" => $uid,
            "result" => $sql
        ];
    }

    /**
     * Get the thread's comments.
     */
    public function show_by_pid($pid) {
        $model = new CommentModel();
        $sql = $model->find_by_project($pid);
        return [
            "pid" => $pid,
            "result" => $sql
        ];
    }

    /**
     * Get the user's comments by given token.
     */
    public function show_by_user_by_token() {
        $user = Auth::user();
        $uid = $user->id;
        $data = $this->show_by_user($uid);
        return [
            "uid" => $uid,
            "result" => $data["result"]
        ];
    }

    /**
     * Get the comment's changelog by given $cid.
     */
    public function show_comment_changelog($cid) {
        $check = DB::table("comment_changelogs")->where("cid", $cid)->get();
        $has_logs = count($check) > 0;
        $message = $has_logs ? "Success" : "No changes";
        return response([
            "cid" => $cid,
            "result" => $check,
            "message" => $message,
        ], 200);
    }
}
