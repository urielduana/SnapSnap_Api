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
        $authUser = auth()->user();
        $search = $request->get('search');

        $users = User::where('name', 'LIKE', "%$search%")
            ->orWhere('username', 'LIKE', "%$search%")
            ->select('id', 'name', 'username')
            ->with(['followers' => function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id);
            }])
            ->get();

        foreach ($users as $user) {
            $profilePhotos = $user->getMedia('profile_photo')->sortByDesc('created_at');
            if ($profilePhotos->isNotEmpty()) {
                $user->profile_photo = $profilePhotos->first()->getFullUrl();
            } else {
                $user->profile_photo = null;
            }

            $user->is_following = $user->followers->isNotEmpty();
        }

        $users = $users->makeHidden('media');

        return response()->json($users);
    }
}
