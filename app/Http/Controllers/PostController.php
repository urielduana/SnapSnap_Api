<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::orderBy('id', 'desc')->get();
        return response()->json($posts);


        //Muestra un form que hice en blade temporalmente
        return view('posts');
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

        try {
            $folder = 'images';

            $post = new Post;

            //datos del post
            $post->description = $request->description;
            //datos temporales
            $post->user_id = 1;
            $post->tag_id = 1;


            $image_url = Storage::disk('s3')->put($folder, $request->image, 'public');

            $post->image_url = $image_url;
            $post->save();

            return response()->json([
                'message' => 'Post creado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el post',$e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post = Post::findorFail($post->id);
            Storage::disk('s3')->delete($post->image_url);

            $post->delete();
            return response()->json([
                'message' => 'Post eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el post'
            ], 500);
        }
    }
}
