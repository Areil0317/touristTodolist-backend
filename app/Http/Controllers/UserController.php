<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except([]);
    }
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => '未找到認證的用戶。'], 404);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->photo = $path;
        $user->save();

        return response([
            'message' => '你已經成功上傳頭貼。'
        ]);
    }
}
