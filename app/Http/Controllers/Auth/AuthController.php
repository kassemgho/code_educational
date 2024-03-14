<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login (Request $request) {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if (auth()->attempt($validatedData)) {
            $user = auth()->user();
            $token = $user->createToken('Personal Token')->plainTextToken;
            return response()->json([
                'token' => $token
            ]);
        }
        return response()->json([
            'error' => 'incorrect information' ,
        ],401);
    }
    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:5', 'confirmed'],
            'role' => ['required']
        ]);

        $user = User::create($request->all());
      
        if ($request->role == 'teacher') {
            $user->teacher()->create();
        }
        else if ($request->role == 'student') {
            $user->student()->create();
        }
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return response()->json([
            'message' => 'registration done succfully...',
            'token' => $token
        ]);
    }
}
