<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Unified API Documentation
Route::get('/documentation', function () {
    return view('api-docs');
})->name('api.docs.unified');
