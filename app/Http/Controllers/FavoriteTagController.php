<?php

namespace App\Http\Controllers;

use App\Models\FavoriteTag;
use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\User;

class FavoriteTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This function returns the info from Tags table of all the tags related to the current user that are favorite
        // Needs this info to show the favorite tags for delete or edit the favorite tags of the user

        // Return all favorite tags for the current user
        $authUser = User::find(auth()->user()->id);
        $authFavoriteTags = $authUser->favoriteTags()->get();

        // Get all Tags from FavoriteTag on a object
        $tagsFavoriteTag = [];
        foreach ($authFavoriteTags as $authFavoriteTag) {
            // Search in Tag table for the id
            $tag = Tags::find($authFavoriteTag->tag_id);
            array_push($tagsFavoriteTag, $tag);
        }

        return response()->json($tagsFavoriteTag);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This function returns the info from Tags table of all the tags related to the current user that are not favorite
        // Needs this info to show the not favorite tags to add to the favorite tags of the user

        // Return all favorite tags for the current user
        $authUser = User::find(auth()->user()->id);
        $authFavoriteTags = $authUser->favoriteTags()->get();
        $tags = Tags::all();

        // Get all Tags from FavoriteTag on a object
        $tagsFavoriteTag = [];
        foreach ($authFavoriteTags as $authFavoriteTag) {
            // Search in Tag table for the id
            $tag = Tags::find($authFavoriteTag->tag_id);
            array_push($tagsFavoriteTag, $tag);
        }

        // Diferece between all tags and favorite tags

        $tagsNotFavoriteTag = $tags->diff($tagsFavoriteTag);

        return response()->json($tagsNotFavoriteTag);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Receive something like this: {favorite_tags: [3, 5, 8, 9, 10]}
        // This function add the favorite tags to the current user

        // Get the favorite tags from the request
        $favoriteTags = $request->favorite_tags;
        // $favoriteTags = json_decode($favoriteTags);

        // Get the current user
        $auth = auth()->user()->id;
        $errorNumber = [];


        // Add the favorite tags to the current user

        foreach ($favoriteTags as $favoriteTagId) {
            try {
                $newFavoriteTag = new FavoriteTag();
                $newFavoriteTag->user_id = $auth;
                $newFavoriteTag->tag_id = $favoriteTagId;
                $newFavoriteTag->save();
            } catch (\Throwable $th) {
                array_push($errorNumber, $favoriteTagId);
            }
        }

        if ($errorNumber) {
            return response()->json($errorNumber, 500);
        } else {
            return response()->json(['message' => 'Favorite tags added successfully'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FavoriteTag $favoriteTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavoriteTag $favoriteTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FavoriteTag $favoriteTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavoriteTag $favoriteTag)
    {
        // Delete the favorite tag from the auth user
        $auth = auth()->user()->id;
        $tag = $favoriteTag->tag_id;

        try {
            $favoriteTag = FavoriteTag::where('user_id', $auth)->where('tag_id', $tag)->first();
            $favoriteTag->delete();
            return response()->json(['message' => 'Favorite tag deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error deleting favorite tag'], 500);
        }
    }
}
