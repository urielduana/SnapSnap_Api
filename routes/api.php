<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sactum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->get('user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return response()->json([
        'message' => 'Tokens Revoked'
    ]);
});

Route::post('verifyEmail', [AuthController::class, 'verifyEmail']);
//Register
Route::post('register', [AuthController::class, 'register']);

/* 
##### Post Routes ########
//muestra el form
Route::get('posts', [PostController::class, 'index']);
//guarda el post
Route::post('postsnew', [PostController::class, 'store']);
 */

//Grupos
Route::get('posts', [PostController::class, 'index']);
Route::post('img', [PostController::class, 'store']);
/* Route::controller(PostController::class)->group(function(){ */
/* }); */