<?php

namespace App\Http\Controllers;

use App\Models\FavoriteTag;
use Illuminate\Http\Request;

class FavoriteTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tags = $request->json()->all();
        foreach ($tags as $tag){
            FavoriteTag::create([
                'user_id'=>1,
                'tag_id'=>$tag['id'],
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
