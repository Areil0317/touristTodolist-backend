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
            $result[] = CommentModel::comment_api_item_formation($item, $item->userdata);
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
        $result = CommentModel::comment_api_item_formation($comment, $comment->userdata);
        $histroy = isset($comment) ? $comment->comment_histroy() : [];
        $code = isset($comment) ? 200 : 404;
        return response([
            "message" => "Success",
            "result" => $result,
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
        $comment = CommentModel::find($id);
        if( $this->user_modification_legal($comment->uid) == false ) {
            return response(["result" => "Not correct user"], 401);
        }
        if( !isset($request->comment) && !isset($request->rate) ) {
            return response(["result" => "Parameter not compeleted"], 400);
        }
        // Set data
        $comment->comment = isset($request->comment) ? $request->comment : $comment->comment;
        $comment->rate = isset($request->rate) ? $request->rate : $comment->rate;
        // Save progress
        $saved = $comment->save();
        $message = $saved ? "Success" : "NOT success";
        $code = $saved ? 200 : 400;
        return response([
            "message" => $message,
            "result" => $comment,
        ], $code);
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
        $saved = $comment->delete();
        if( $saved ) {
            return response(["message" => "Success"], 200);
        } else {
            return response(["message" => "NOT success"], 400);
        }
    }
}
