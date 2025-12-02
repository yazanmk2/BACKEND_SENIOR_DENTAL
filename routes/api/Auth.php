<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ApplicationRateController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UpdatePhotoController;
use App\Http\Controllers\Auth\DeletePhotoController;
use App\Http\Controllers\Auth\UpdateProfileController;


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rate-app', [ApplicationRateController::class, 'submit']);
    Route::post('/update-photo', [UpdatePhotoController::class, 'update']);
    Route::delete('/delete-photo', [DeletePhotoController::class, 'delete']);
    Route::post('/update-profile', [UpdateProfileController::class, 'update']);


});