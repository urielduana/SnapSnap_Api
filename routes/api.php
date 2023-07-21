<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteTagController;
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
Route::get('tags', [TagsController::class, 'index']);


// Test
Route::get('posts', [PostController::class, 'index']);
Route::post('img', [PostController::class, 'store']);
// getProfilePhoto
Route::middleware('auth:sanctum')->get('profile_photo/', [AuthController::class, 'getProfilePhoto']);

Route::post('/tags', [FavoriteTagController::class, 'store']);
