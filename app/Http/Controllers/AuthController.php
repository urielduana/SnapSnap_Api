<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use \stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        };

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hola ' . $user->name,
            'accesToken' => $token,
            'tokenType' => 'Bearer',
            'user' =>   $user,
        ]);
    }
    // This function verifies if the email is not registered in the database

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if ($user) {
            return response()->json(['message' => 'Email already registered'], 401);
        }
    }

    public function verifyUsername(Request $request)
    {
        $user = User::where('username', $request->username)->firstOrFail();

        if ($user) {
            return response()->json(['message' => 'Username already registered'], 401);
        }
    }

    // public function logout(Request $request)
    // {
    //     auth()->user()->tokens()->delete();
    //     return response()->json(['message' => 'Logged out']);
    // }
}
