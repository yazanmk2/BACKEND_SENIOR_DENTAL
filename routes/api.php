<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ai\DetectTeethController;



// ✅ Load routes for each actor
include __DIR__ . '/api/customer.php';
include __DIR__ . '/api/doctor.php';

// ✅ Load authentication routes
include __DIR__ . '/api/Auth.php';


Route::get('/create-token', function () {
    $user = User::find(1);
    return $user->createToken('token')->plainTextToken;
});
Route::get('/me', function () {
    return auth()->user();
})->middleware('auth:sanctum');

Route::fallback(function () {
    return response()->json(['message' => 'Page not found.'], 404);
});


Route::middleware('auth:sanctum')->post(
    '/ai/detect-teeth',
    [DetectTeethController::class, 'detect']
);
