<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteTagController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

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

// Login and Logout
Route::post('sanctum/token', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('user/revoke', [AuthController::class, 'logout']);

// Register
Route::post('register', [AuthController::class, 'register']);
Route::post('register/email', [AuthController::class, 'verifyEmail']);
Route::post('register/username', [AuthController::class, 'verifyUsername']);
Route::middleware('auth:sanctum')->post('register/profile_photo', [AuthController::class, 'uploadProfilePhoto']);

// Tags
//Route::middleware('auth:sanctum')->resource('tags', TagsController::class);
Route::get('tags', [TagsController::class, 'index']);

// Favorite Tags
Route::middleware('auth:sanctum')->resource('favorite_tags', FavoriteTagController::class);

// Users

// Search Users
Route::middleware('auth:sanctum')->post('users/search', [UserController::class, 'searchUsers']);

// Follow user
Route::middleware('auth:sanctum')->post('users/follow', [UserController::class, 'followUser']);
Route::middleware('auth:sanctum')->post('users/unfollow', [UserController::class, 'unFollowUser']);

// Profile
Route::middleware('auth:sanctum')->get('profile/{user_id}', [UserController::class, 'profile']);
Route::middleware('auth:sanctum')->post('profile/posts/{user_id}/{tag_id}', [UserController::class, 'profileTagPost']);



// Test
Route::get('posts', [PostController::class, 'index']);
Route::post('img', [PostController::class, 'store'])->middleware('auth:sanctum');
// getProfilePhoto
Route::middleware('auth:sanctum')->get('profile_photo/', [AuthController::class, 'getProfilePhoto']);

// Route::post('/tags', [FavoriteTagController::class, 'store']);
Route::middleware('auth:sanctum')->get('feed', [PostController::class, 'feed']);

Route::middleware('auth:sanctum')->post('posts/{post}/like', [PostController::class, 'togglelike']);

// Ruta para obtener los comentarios de un post
Route::get('posts/{post}/comments', [CommentController::class, 'index']);
// Ruta para crear un comentario
Route::middleware('auth:sanctum')->post('posts/{post}/comments', [CommentController::class, 'store']);