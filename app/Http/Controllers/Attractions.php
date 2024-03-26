<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AttractionModel;

class Attractions extends Controller
{
    private function api_formation($result, $input = "", $input_message = "") {
        $default_message = $result ? "Success" : "Error";
        return [
            "message" => $input_message ? $input_message : $default_message,
            "input" => $input,
            "result" => $result,
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $command = AttractionModel::all();
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
        $code = $command ? 200 : 400;
        return response( $this->api_formation($command, $request->aname), $code )->header("Access-Control-Allow-Origin", "*");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = AttractionModel::find($id);
        $code = $item ? 200 : 400;
        return response( $this->api_formation($item, $id), $code)->header("Access-Control-Allow-Origin", "*");
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
        if (!$attraction) {
            return response()->json(
                $this->api_formation(
                    $attraction,
                    $id,
                    "Attraction not found"
                ),
                404
            )->header("Access-Control-Allow-Origin", "*");
        }

        $attraction->delete();

        return response(
            $this->api_formation(
                $attraction,
                $id,
                "Attraction deleted successfully"
            ),
            200
        )->header("Access-Control-Allow-Origin", "*");
    }

    public function show_by_name(string $aname)
    {
        $command = AttractionModel::where("aname", $aname)->first();
        $code = $command ? 200 : 404;
        $message = $command ? "Success" : "No data";
        $api = $this->api_formation(
            $command,
            $aname,
            $message
        );
        return response( $api, $code, )->header("Access-Control-Allow-Origin", "*");
    }
}
