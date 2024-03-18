<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentModel;
use Illuminate\Support\Facades\Auth;

class Comments extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except([
            "index",
            "show",
            "no_id_given",
            "show_by_user",
            "show_by_pid",
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comment = CommentModel::with("userdata")->get();
        $result = array();
        foreach( $comment as $item ) {
            $result[] = CommentModel::api_item_formation($item);
        }
        return [
            "message" => "Success",
            "result" => $result,
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
            "pid" => "required",
            "comment" => "required|string|max:150",
            "rate" => "required|numeric|between:1,10",
        ]);
        if( $validated ) {
            $user = Auth::user();
            $comment = CommentModel::create([
                "uid" => $user->id,
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = CommentModel::with("userdata")->find($id);
        $histroy = isset($comment) ? $comment->comment_histroy() : [];
        $code = isset($comment) ? 200 : 404;
        return response([
            "result" => CommentModel::api_item_formation($comment),
            "histroy" => $histroy,
        ], $code);
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
     * Check if input user is legal user.
     */
    private function user_modification_legal($comment_uid) {
        $user = Auth::user();
        return $user->id == $comment_uid;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if( !isset($request->comment) && !isset($request->rate) ) {
            return response(["result" => "Parameter not compeleted"], 400);
        }
        $comment = CommentModel::find($id);
        if( $this->user_modification_legal($comment->uid) == false ) {
            return response(["result" => "Not correct user"], 401);
        }
        // Set data
        $comment->comment = isset($request->comment) ? $request->comment : $comment->comment;
        $comment->rate = isset($request->rate) ? $request->rate : $comment->rate;
        // Save progress
        $saved = $comment->save();
        if( $saved ) {
            return response([
                "message" => "Success",
                "result" => $comment,
            ], 200);
        } else {
            return response([
                "result" => "NOT success"
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = CommentModel::find($id);
        if( $this->user_modification_legal($comment->uid) == false ) {
            return response(["result" => "Not correct user"], 401);
        }
        // Progress
        $saved = $comment-remove();
        if( $saved ) {
            return response(["message" => "Success"], 200);
        } else {
            return response(["result" => "NOT success"], 400);
        }
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
}
