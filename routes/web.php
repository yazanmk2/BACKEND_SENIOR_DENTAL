<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect to API Documentation
Route::get('/documentation', function () {
    return redirect('/api/documentation');
})->name('api.docs.unified');
