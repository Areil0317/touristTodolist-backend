<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pre;
use Illuminate\Http\Request;

class PreController extends Controller
{
    public function index()
    {
        $pres = DB::select('SELECT * FROM `prepare`');
        return response()->json($pres);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pretitle' => 'required|string',
            'pretext' => 'nullable|string',
            'type' => 'nullable|string|max:2',
            'checked' => 'nullable|boolean',
        ]);

        $pre = DB::table('prepare')->insertGetId($validatedData);
        return response()->json(['preid' => $pre, 'data' => $validatedData], 201);
    }

    public function update(Request $request, $preid)
    {
        $validatedData = $request->validate([
            'pretitle' => 'required|string',
            'pretext' => 'nullable|string',
            'type' => 'nullable|string|max:2',
            'checked' => 'nullable|boolean',
        ]);

        DB::table('prepare')
            ->where('preid', $preid)
            ->update($validatedData);

        $updatedPre = DB::table('prepare')
            ->where('preid', $preid)
            ->first();

        return response()->json($updatedPre);
    }

    public function destroy($preid)
    {
        DB::table('prepare')
            ->where('preid', $preid)
            ->delete();

        return response()->json(['message' => 'Pre deleted']);
    }
}