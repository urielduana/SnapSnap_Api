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
        // Add last time profile photo url from spaite media library
        foreach ($users as $user) {
            $user->profile_photo_url = $user->getFirstMediaUrl('profile_photo');
        }
        // Remove "media" object from response
        $users = $users->makeHidden('media');
        // Return json response
        return response()->json($users);
    }
}
