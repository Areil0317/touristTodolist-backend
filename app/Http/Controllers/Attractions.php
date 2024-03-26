<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AttractionModel;

class Attractions extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $command = AttractionModel::all();
        return response([
            "message" => "Success",
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
     * Store a newly created resource in storage. Result returns:
     *
     * 1. an `id` if success sotred.
     * 2. a data if the data already exist.
     * 3. `false` if the `aname` param does not exist.
     */
    public function store(Request $request)
    {
        $request->validate([
            "aname" => "required|unique:attractions,aname"
        ]);
        $command = AttractionModel::create([
            "aname" => $request->aname
        ]);

        // Response formats
        $code = $command ? 200 : 400;
        $message = $command ? "Success" : "Attraction NOT created";

        // Result
        return response()->json([
            "message" => $message,
            "input" => $request->aname,
            "result" => $command,
        ], $code)->header("Access-Control-Allow-Origin", "*");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attraction = AttractionModel::find($id);
        if( $attraction ) {
            return $this->success_response( "Success", $id, $attraction );
        }
        return $this->error_response( "Not found", $id, $attraction, 404 );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response([
            "message" => "Method not supported"
        ], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response([
            "message" => "Method not supported"
        ], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attraction = AttractionModel::find($id);
        if ($attraction) {
            $attraction->delete();
            return $this->success_response( "Attraction deleted successfully", $id, $attraction );
        }
        return $this->error_response( "Attraction not found", $id, $attraction, 404 );
    }

    public function show_by_name(string $aname)
    {
        $attraction = AttractionModel::where("aname", $aname)->first();
        if( $attraction ) {
            return $this->success_response( "Success", $aname, $attraction );
        }
        return $this->error_response( "Not found", $aname, $attraction, 404 );
    }
    private function success_response($message, $input, $result) {
        return response()->json([
            "message" => $message,
            "input" => $input,
            "result" => $result,
        ], 200)->header("Access-Control-Allow-Origin", "*");
    }
    private function error_response($message = "Error", $input, $result, $code = 400) {
        return response()->json([
            "message" => $message,
            "input" => $input,
            "result" => $result,
        ], $code)->header("Access-Control-Allow-Origin", "*");
    }
}
