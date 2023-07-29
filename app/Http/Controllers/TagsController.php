<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use App\Models\User;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This function returns the info from Tags table of all the tags related to the current user

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tags $tags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tags $tags)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tags $tags)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tags $tags)
    {
        //
    }
}
