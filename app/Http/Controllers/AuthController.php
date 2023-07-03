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
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        };

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($request->email))),
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
