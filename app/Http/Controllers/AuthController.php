<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use \stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //
    public function register(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'User created successfully'], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The credentials you entered are incorrect. Please try again'], 401);
        }
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Tokens Revoked'
        ]);
    }   

    public function verifyEmail(Request $request)
    {
        if ($request->email == null) {
            return response()->json(['message' => 'Email is required'], 401);
        }
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            if ($user) {
                return response()->json(['message' => 'Email already registered'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Email available'], 200);
        }
    }

    public function verifyUsername(Request $request)
    {
        if ($request->username == null) {
            return response()->json(['message' => 'Username is required'], 401);
        }
        try {
            $user = User::where('username', $request->username)->firstOrFail();
            if ($user) {
                return response()->json(['message' => 'Username already registered'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Username available'], 200);
        }
    }
}
