<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Comments extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            "message" => "Hello comment"
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $sql = DB::select("SELECT tid as thread, comment, rate, date FROM `comments` WHERE uid = ? ORDER BY date ASC", [$uid]);
        return [
            "uid" => $uid,
            "result" => $sql
        ];
    }

    /**
     * Get the thread's comments.
     */
    public function show_by_thread($tid) {
        $sql = DB::select("SELECT uid as user, comment, rate, date FROM `comments` WHERE tid = ? ORDER BY date ASC", [$tid]);
        return [
            "tid" => $tid,
            "result" => $sql
        ];
    }
}
