<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PlaceController;

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
Route::apiResource('files', FileController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('places', PlaceController::class);

Route::post('files/{file}', [FileController::class, 'update_workaround']);
Route::post('posts/{post}', [PostController::class, 'update_workaround']);
Route::post('places/{place}', [PlaceController::class, 'update_workaround']);

Route::post('posts/{post}/like', [PostController::class, 'like']);
Route::delete('posts/{post}/like', [PostController::class, 'like']);

Route::post('places/{place}/favorites', [PlaceController::class, 'favorite']);
Route::delete('places/{place}/favorites', [PlaceController::class, 'favorite']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', [TokenController::class, 'user'])->middleware('auth:sanctum');
Route::post('register', [TokenController::class, 'register'])->middleware('guest');
Route::post('login', [TokenController::class, 'login'])->middleware('guest');
Route::post('logout', [TokenController::class, 'logout'])->middleware('auth:sanctum');
