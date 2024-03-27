<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pre;
use Illuminate\Http\Request;


class PreController extends Controller
{
    public function index()
    {
        $pres =DB::select('SELECT * FROM `prepare`');
        return response()->json($pres);
        
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'preititle' => 'required|string',
            'pretext' => 'nullable|string',
            'checked' => 'nullable|string|max:2',
            'type' => 'nullable|string|max:2',
        ]);

        $pre = Pre::create($validatedData);
        return response()->json($pre);
    }

    public function update(Request $request, Pre $pre)
    {
        $validatedData = $request->validate([
            'preititle' => 'required|string',
            'pretext' => 'nullable|string',
            'checked' => 'nullable|string|max:2',
            'type' => 'nullable|string|max:2',
        ]);

        $pre->update($validatedData);
        return response()->json($pre);
    }

    public function destroy(Pre $pre)
    {
        $pre->delete();
        return response()->json(['message' => 'Pre deleted']);
    }
}