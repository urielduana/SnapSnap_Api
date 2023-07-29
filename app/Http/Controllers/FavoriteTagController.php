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
        $tags = $request->json()->all();
        foreach ($tags as $tag) {
            FavoriteTag::create([
                'user_id' => 1,
                'tag_id' => $tag['id'],
            ]);
        }
        return response()->json(['message' => 'Tags guardados correctamente', 'tags' => $tags]);
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
        //
    }
}
