<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tags;
use App\Models\Post;

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

    public function profile($user_id)
    {
        $user = User::find($user_id);
        $user->followers_count = $user->followers->count();
        $user->following_count = $user->following->count();

        $user->load(['favoriteTags.tag.posts' => function ($query) {
            $query->orderBy('created_at', 'desc')->first();
        }]);

        return response()->json($user);
    }

    public function profileTagPost($user_id, $tag_id)
    {
        $tag = Tags::find($tag_id);

        $post = Post::where('user_id', $user_id)
            ->where('tag_id', $tag_id)
            ->get();

        $data = [
            'tag' => $tag,
            'post' => $post,
        ];

        return response()->json($data);
    }
}
