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

        /* $posts = Post::orderBy('id', 'desc')->get();
        return response()->json($posts);
 */

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
        //Usando spatie y aws s3
        try {
            $folder = 'images';

            // info user auth
            $user = auth()->user();
            
            $image_url = Storage::disk('s3')->put($folder, $request->image, 'public');

            $post = Post::create([
                'description' => $request->description,
                //'user_id' => $user->id, -> esto es para cuando tengamos el login
                //'tag_id' => $request->tag_id, -> esto es para cuando tengamos el login
                'user_id' => 1,
                'tag_id' => 1,
                'image_url' => $image_url
            ]);


            $post->save();

            if (isset($request['image'])) {
                $post->addMediaFromRequest('image')->toMediaCollection('posts');
            }

            return response()->json([
                'message' => 'Post creado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el post', $e
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
