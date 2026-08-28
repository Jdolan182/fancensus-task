<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YoutubeController;

Route::get('/', function () {
    return view('youtubeProcessor');
});

Route::get('/youtube/video', [YoutubeController::class, 'getVideo']);