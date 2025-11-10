<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customer\CustomerInfoController;

Route::prefix('customer')->middleware('auth:sanctum')->group(function () {
    Route::post('/info', [CustomerInfoController::class, 'uploadInfo']);
});
