<?php

namespace App\Http\Controllers;

use App\Models\Follower;
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
            ->withCount(['followers as is_following' => function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id);
            }])
            ->with(['followers' => function ($query) {
                $query->select('id', 'follower_id', 'user_id');
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


    public function followUser(Request $request)
    {
        $authUser = auth()->user();
        $user = User::find($request->user_id);

        $follower = Follower::create([
            'user_id' => $authUser->id,
            'follower_id' => $user->id,
        ]);

        return response()->json($follower);
    }

    public function unFollowUser(Request $request)
    {
        $authUser = auth()->user();
        $user = User::find($request->user_id);

        $follower = Follower::where('user_id', $authUser->id)
            ->where('follower_id', $user->id)
            ->first();

        $follower->delete();

        return response()->json($follower);
    }

    public function userProfile(Request $request)
    {
        $user = User::find($request->user_id);
        $user->favoriteTags->load('tag');
        // Add last post of each tag
        foreach ($user->favoriteTags as $favoriteTag) {
            $favoriteTag->tag->load(['posts' => function ($query) {
                $query->orderBy('created_at', 'desc')->first();
            }]);
        }
        $user->followers_count = $user->followers->count();

        return response()->json($user);
    }
}
