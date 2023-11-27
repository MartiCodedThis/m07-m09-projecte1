<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PlaceController; 
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    Log::info('Loading welcome page');
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    $request->session()->flash('info', 'TEST flash messages'); //TODO
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('mail/test', [MailController::class, 'test']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('files', FileController::class)->middleware(['auth', 'role:3']);

Route::resource('places', PlaceController::class)->middleware(['auth', 'role:3']);

Route::resource('posts', PostController::class)->middleware(['auth', 'role:3']);
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::delete('/posts/{post}/like', [PostController::class, 'like'])->name('posts.unlike');

Route::post('places/{place}/favorites', [PlaceController::class, 'favorite'])->name('places.favorite');
Route::delete('places/{place}/favorites', [PlaceController::class, 'favorite'])->name('places.favorite');


require __DIR__.'/auth.php';
