<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentModel;

class Comments extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comment = CommentModel::get();
        return [
            "message" => "Hello comment",
            "result" => $comment,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response([
            "message" => "Not supported"
        ], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "uid" => "required",
            "pid" => "required",
            "comment" => "required|string|max:150",
            "rate" => "required|numeric|between:1,10",
        ]);
        if( $validated ) {
            $comment = CommentModel::create([
                "uid" => $validated["uid"],
                "pid" => $validated["pid"],
                "comment" => $validated["comment"],
                "rate" => $validated["rate"],
            ]);
            $message = $comment ? "Comment created" : "Comment NOT created";
            $code = $comment ? 200 : 400;
            return response([
                "message" => $message,
                "result" => $comment,
            ], $code);
        } else {
            return response([
                "message" => "Comment NOT created",
                "result" => [],
            ], 400);
        }

        // ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = CommentModel::find($id);
        return response([
            "message" => "Testing...",
            "result" => $comment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response([
            "message" => "Not supported"
        ], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * No ID given.
     */
    public function no_id_given() {
        return response([
            "message" => "Please provide ID for querying."
        ], 400);
    }

    /**
     * Get the user's comments.
     */
    public function show_by_user($uid) {
        $sql = DB::select("SELECT pid as project, comment, rate, date FROM `comments` WHERE uid = ? ORDER BY date ASC", [$uid]);
        return [
            "uid" => $uid,
            "result" => $sql
        ];
    }

    /**
     * Get the thread's comments.
     */
    public function show_by_thread($pid) {
        $sql = DB::select("SELECT uid as user, comment, rate, date FROM `comments` WHERE pid = ? ORDER BY date ASC", [$pid]);
        return [
            "pid" => $pid,
            "result" => $sql
        ];
    }
}
