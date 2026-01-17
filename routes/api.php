<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Ai\DetectTeethController;
use App\Http\Controllers\Ai\DiagnoseOrthodonticsController;
use App\Http\Controllers\Ai\DoctorDetectTeethController;
use App\Http\Controllers\HealthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are prefixed with /api by default (see RouteServiceProvider).
| API versioning is implemented using /v1 prefix for all routes.
|
*/

// ==========================================================================
// Health Check (Public - No Auth Required)
// ==========================================================================
Route::get('/health', HealthController::class)->name('health');

// ==========================================================================
// API Version 1 Routes
// ==========================================================================
Route::prefix('v1')->name('v1.')->group(function () {

    // Load chat routes (includes public health check)
    include __DIR__ . '/api/chat.php';

    // Load authentication routes
    include __DIR__ . '/api/Auth.php';

    // Load actor-specific routes
    include __DIR__ . '/api/customer.php';
    include __DIR__ . '/api/doctor.php';

    // AI Routes (Protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/ai/detect-teeth', [DetectTeethController::class, 'detect'])
            ->name('ai.detect-teeth');

        Route::post('/ai/diagnose-orthodontics', [DiagnoseOrthodonticsController::class, 'diagnose'])
            ->name('ai.diagnose-orthodontics');

        Route::post('/ai/doctor/detect-teeth', [DoctorDetectTeethController::class, 'detect'])
            ->name('ai.doctor.detect-teeth');
    });

});

// ==========================================================================
// Legacy Routes (Without v1 prefix - for backward compatibility)
// ==========================================================================
// TODO: Deprecate these routes after frontend migration to v1

// Load routes for each actor (legacy)
include __DIR__ . '/api/customer.php';
include __DIR__ . '/api/doctor.php';

// Load authentication routes (legacy)
include __DIR__ . '/api/Auth.php';

// Development helper routes
if (config('app.debug')) {
    Route::get('/create-token', function () {
        $user = User::find(1);
        return $user->createToken('token')->plainTextToken;
    });
}

Route::get('/me', function () {
    return auth()->user();
})->middleware('auth:sanctum');

// Legacy AI routes
Route::middleware('auth:sanctum')->post(
    '/ai/detect-teeth',
    [DetectTeethController::class, 'detect']
);

Route::middleware('auth:sanctum')->group(function () {
    Route::post(
        '/diagnose-orthodontics',
        [DiagnoseOrthodonticsController::class, 'diagnose']
    );
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post(
        '/ai/doctor/detect-teeth',
        [DoctorDetectTeethController::class, 'detect']
    );
});

// ==========================================================================
// Fallback Route
// ==========================================================================
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint not found.',
        'hint' => 'Check the API documentation for available endpoints.'
    ], 404);
});
