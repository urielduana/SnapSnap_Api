<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // Return json response
        return response()->json($users);
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('search');
        $users = User::where('name', 'LIKE', "%$search%")
            ->orWhere('username', 'LIKE', "%$search%")
            ->select('id', 'name', 'username')
            ->get();
        // Get all profile photos of users
        foreach ($users as $user) {
            $profilePhotos = $user->getMedia('profile_photo')->sortByDesc('created_at');
            if ($profilePhotos->isNotEmpty()) {
                $user->profile_photo = $profilePhotos->first()->getFullUrl();
            } else {
                $user->profile_photo = null;
            }
        }
        // Remove "media" object from response
        $users = $users->makeHidden('media');
        // Return json response
        return response()->json($users);
    }
}
