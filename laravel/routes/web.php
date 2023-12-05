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
use App\Http\Controllers\AboutController;
use App\Http\Controllers\LanguageController;

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
include __DIR__.'/assets.php';


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

Route::resource('files', FileController::class)->middleware(['auth',]);

Route::resource('places', PlaceController::class)->middleware(['auth',]);

Route::resource('posts', PostController::class)->middleware(['auth',]);

Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::delete('/posts/{post}/like', [PostController::class, 'like'])->name('posts.unlike');

Route::post('places/{place}/favorites', [PlaceController::class, 'favorite'])->middleware('can:favorite,place')->name('places.favorite');
Route::delete('places/{place}/favorites', [PlaceController::class, 'favorite'])->middleware('can:favorite,place')->name('places.favorite');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/language/{locale}', [LanguageController::class, 'language'])->name('language');

Route::get('logo', function () {
    $path = public_path('logos/logo.png');
    return response()->file($path);
})->name('logo');



require __DIR__.'/auth.php';
