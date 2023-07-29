<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PostController extends Controller implements HasMedia
{
    use InteractsWithMedia;
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
	try {
            $caption = $request->input('caption');
            $tags = explode(',', $request->input('tags'));
            $images = $request->file('images');

            // Validar que el usuario esté autenticado
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            // Validar que se hayan enviado imágenes
            if (!$images) {
                return response()->json(['error' => 'No se enviaron imágenes válidas.'], 400);
            }

            $urls = [];
	    $s3 = Storage::disk('s3');

            // Guardar las imágenes en S3
            foreach ($images as $image) {
                if (!$image->isValid()) {
                    continue;
                }

                $path = 'posts/' . time() . '_' . $image->getClientOriginalName();
                $s3->put($path, file_get_contents($image), 'public');
                $url = Storage::url($path);
                $urls[] = $url;
	    }

            // Crear el post
            $post = new Post();
            $post->user_id = $user->id;
	    $post->description = $caption;
	    //Falta arreglar esto de los multiples tags
            $post->tag_id = $tags[0];
	    $post->save();


	// Guardar las imágenes utilizando Laravel-MediaLibrary
        foreach ($images as $image) {
            if (!$image->isValid()) {
                continue;
            }

            $media = $post->addMedia($image)
                          ->toMediaCollection('posts');

            $urls[] = $media->getFullUrl();
        }



            return response()->json(['urls' => $urls, 'user' => $user, 'caption' => $caption, 'tags' => $tags], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar las imágenes.'], 500);
        }
}

    public function avatar(Request $request){
          try {
            $folder = 'avatar';

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
