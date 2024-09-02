<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $token = $user->createToken('Personal Access Token')->plainTextToken;


        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
    

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); 
            $token = $user->createToken('LaravelSanctum')->plainTextToken; 
    

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
