<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;

/*
|--------------------------------------------------------------------------
| Chat API Routes
|--------------------------------------------------------------------------
|
| Routes for the AI chatbot integration. These endpoints allow authenticated
| users to interact with the dental FAQ chatbot.
|
*/

// Public health check endpoint (for monitoring)
Route::get('/chat/health', [ChatController::class, 'health'])
    ->name('chat.health');

// Protected chat endpoints (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Send a message to the chatbot
    Route::post('/chat/message', [ChatController::class, 'sendMessage'])
        ->name('chat.message');

    // Get chat history (placeholder for future implementation)
    Route::get('/chat/history', [ChatController::class, 'getHistory'])
        ->name('chat.history');

});
