<?php

namespace App\Http\Controllers;

use App\Models\CommentModel;
use App\Models\JimageModel;
use App\Models\JourneyModel;
use App\Models\JourneyProjectModel;
use App\Models\JpimageModel;
use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\CssSelector\Parser\Handler\CommentHandler;

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
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->photo = $path;
        $user->save();

        return response([
            'message' => '你已經成功上傳頭貼。'
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => '未找到認證的用戶。'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'cellphone' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'cellphone' => $request->cellphone,
        ]);

        return response()->json([
            'message' => 'User information updated successfully!',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password updated successfully!']);
    }

    public function calculateScore(Request $request)
    {
        $user = $request->user(); // 獲取當前認證的用戶

        // 計算 TouristList 分數
        $touristListScore = ListModel::where('uid', $user->id)->count() * 5;

        // 計算 Journey list 相關分數
        $journeys = JourneyModel::whereIn('tlid', ListModel::where('uid', $user->id)->pluck('tlid'))->get();
        $journeyScore = $journeys->count() * 5;

        // 計算 Journey image 相關分數
        $jimageScore = JimageModel::whereIn('jid', $journeys->pluck('jid'))->count() * 20;
        $journeyProjectScore = JourneyProjectModel::whereIn('jid', $journeys->pluck('jid'))->count() * 5;

        // 計算 JourneyProject 相關分數
        $jpimageScore = JpimageModel::whereIn('jpid', JourneyProjectModel::whereIn('jid', $journeys->pluck('jid'))->pluck('jpid'))->count() * 20;

        //計算 comment 分數
        $commentscore = CommentModel::where('uid', $user->id)->count() * 20;

        // 總分
        $totalScore = $touristListScore + $journeyScore + $jimageScore + $journeyProjectScore + $jpimageScore + $commentscore;

        return response()->json([
            'totalScore' => $totalScore
        ])
            ->header('Access-Control-Allow-Origin', '*');
    }
}
