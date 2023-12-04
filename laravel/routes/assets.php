<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;



Route::prefix('assets')->group(function () {

    // GENERAL

    Route::get('logo', function () {
        $path = public_path('logos/logo.png');
        return response()->file($path);
    })->name('logo');

    // PAGES INDEX
    
    Route::get('locationpin', function () {
        $path = public_path('assets/locationpin.png');
        return response()->file($path);
    })->name('locationpin');
    
    Route::get('worldmap', function () {
        $path = public_path('assets/worldmap.png');
        return response()->file($path);
    })->name('worldmap');

    Route::get('chaticon', function () {
        $path = public_path('assets/chaticon.png');
        return response()->file($path);
    })->name('chaticon');

    Route::get('searchicon', function () {
        $path = public_path('assets/searchicon.png');
        return response()->file($path);
    })->name('searchicon');

    // ABOUT US

    Route::get('serious-mst', function () {
        $path = public_path('assets/serious-mst.jpg');
        return response()->file($path);
    })->name('serious-mst');

    Route::get('funny-mst', function () {
        $path = public_path('assets/funny-mst.jpg');
        return response()->file($path);
    })->name('funny-mst');

    Route::get('serious-mre', function () {
        $path = public_path('assets/serious-mre.jpg');
        return response()->file($path);
    })->name('serious-mre');

    Route::get('funny-mre', function () {
        $path = public_path('assets/funny-mre.jpg');
        return response()->file($path);
    })->name('funny-mre');

    // ABOUT AUDIOS

    Route::get('sandstorm', function () {
        $path = public_path('assets/audio/sandstorm.mp3');
        return response()->file($path);
    })->name('sandstorm');

    Route::get('spiderman', function () {
        $path = public_path('assets/audio/spiderman.mp3');
        return response()->file($path);
    })->name('spiderman');
});


