<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;
use App\Http\Controllers\FileController;

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
   $request->session()->flash('info', 'TEST flash messages');
   return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::get('mail/test', [MailController::class, 'test']);

Route::resource('files', FileController::class);


