<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token,
        ], 201)
            ->header('Access-Control-Allow-Origin', '*');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User successfully logged in',
            'user' => $user,
            'token' => $token,
        ])
            ->header('Access-Control-Allow-Origin', '*');
        ;
    }

   
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function sendResetLinkEmail(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);


        $user = User::where('email', $request->email)->firstOrFail();
        $newPassword = rand(10000000, 99999999);  // 隨機生成新密碼
        $user->password = Hash::make($newPassword);
        $user->save();



        try {
            
            Mail::to($user->email)->send(new ResetPasswordMail($newPassword));
            return response()->json(['message' => '新密碼已經發送到您的郵箱。']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ]);
        }


    }

}
