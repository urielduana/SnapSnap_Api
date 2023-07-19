<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\FavoriteTag;
use \stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        $user->save();

        // Give default favorite tags to user id  1 and 2
        $favoriteTags = new FavoriteTag();
        $favoriteTags->user_id = $user->id;
        $favoriteTags->tag_id = 1;
        $favoriteTags->save();
        $favoriteTags = new FavoriteTag();
        $favoriteTags->user_id = $user->id;
        $favoriteTags->tag_id = 2;
        $favoriteTags->save();
        // Return user token
        return $user->createToken($request->device_name)->plainTextToken;

        // if ($request->hasFile('profile_photo')) {
        //     try {
        //         $user->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo', 's3');
        //         return response()->json(['message' => 'User created successfully with profile photo'], 200);
        //     } catch (\Throwable $th) {
        //         // Create profile photo using gravatar api
        //         $user->addMediaFromUrl('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))))->toMediaCollection('profile_photo', 's3');
        //         return response()->json(['message' => 'User created successfully but profile photo not uploaded'], 200);
        //     }
        // } else {
        //     $user->addMediaFromUrl('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))))->toMediaCollection('profile_photo', 's3');
        //     return response()->json(['message' => 'User created successfully'], 200);
        // }
    }

    public function uploadProfilePhoto(Request $request)
    {
        $user = $request->user();
        if ($request->hasFile('profile_photo')) {
            try {
                $user->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo', 's3/profile_photos');
                return response()->json(['message' => 'Profile photo uploaded successfully'], 200);
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Profile photo not uploaded try catch error'], 500);
            }
        } else {
            return response()->json(['message' => 'Profile photo not uploaded'], 500);
        }
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
    // Returns last media of the user giving the sanctum token

    public function getProfilePhoto(Request $request)
    {
        $user = $request->user();
        $media = $user->getMedia('profile_photo')->last();
        if ($media) {
            $media->url = $media->getUrl();
            // return response()->json(['message' => 'Profile photo found', 'data' => $media], 200);
            return $media;
        } else {
            return response()->json(['message' => 'Profile photo not found'], 404);
        }
    }
}
