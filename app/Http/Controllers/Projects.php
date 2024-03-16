<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Projects extends Controller
{
    private function api_formation($actrion_success, $input = "") {
        $message = $actrion_success ? "Success" : "Error";
        return [
            "message" => $message,
            "input" => $input,
            "result" => $actrion_success,
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $command = DB::table("project")->get();
        return response([
            "result" => $command
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response([
            "message" => "Method not supported"
        ], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = [
            "aid" => $request->aid,
            "pname" => $request->pname,
        ];
        $command = DB::table("project")->insert($input);
        $message = $command ? "Success" : "Error";
        $code = $command ? 200 : 400;
        return response($this->api_formation($command, $input), $code)->header("Access-Control-Allow-Origin", "*");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $command = DB::table("project")->where("pid", [$id])->get();
        $message = $command ? "Success" : "Error";
        $code = $command ? 200 : 400;
        return response($this->api_formation($command, $id), $code)->header("Access-Control-Allow-Origin", "*");
    }

    private function get_aid(string $aname) {
        $attraction_cont = new Attractions();
        $aid_src = $attraction_cont->show_by_name($aname);
        return $aid_src->original["result"][0]->aid;
    }

    public function show_by_attraction(string $aname)
    {
        $aid = $this->get_aid($aname);
        $command = DB::table("project")->where("aid", [$aid])->get();
        $message = $command ? "Success" : "Error";
        $code = $command ? 200 : 400;
        return response($this->api_formation($command, $aname), $code)->header("Access-Control-Allow-Origin", "*");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response([
            "message" => "Method not supported"
        ], 405);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response([
            "message" => "Method not supported"
        ], 405);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $command = DB::table("project")->where("pid", [$id])->delete();
        $message = $command ? "Success" : "Error";
        $code = $command ? 200 : 400;
        return response($this->api_formation($command, $id), $code)->header("Access-Control-Allow-Origin", "*");
    }
}
