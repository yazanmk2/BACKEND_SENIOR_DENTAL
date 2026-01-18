<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\UpdateDoctorInfoController;
use App\Http\Controllers\Doctor\UploadDisplayCaseController;
use App\Http\Controllers\Doctor\GetDoctorDisplayCasesController;
use App\Http\Controllers\Doctor\UpdateDisplayCaseFavoriteController;
use App\Http\Controllers\Doctor\DeleteDisplayCaseController;
use App\Http\Controllers\Doctor\GetBookingsByStatusController;
use App\Http\Controllers\Doctor\UpdateBookingStatusController;
use App\Http\Controllers\Doctor\CompleteBookingController;
use App\Http\Controllers\Doctor\PanoramaTeethController;
use App\Http\Controllers\Doctor\TodayApprovedBookingsController;
use App\Http\Controllers\Doctor\UpdateToothController;
use App\Http\Controllers\Doctor\DeleteToothController;
use App\Http\Controllers\Doctor\StoreToothController;
use App\Http\Controllers\Doctor\GetDoctorPanoramasController;
use App\Http\Controllers\Doctor\CreateTeethDoctorController;
use App\Http\Controllers\Doctor\UpdateTeethDoctorController;
use App\Http\Controllers\Doctor\TeethDoctorController;
use App\Http\Controllers\Doctor\GetTeethByPanoramaController;


Route::prefix('doctor')->group(function (): void {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/profile', [UpdateDoctorInfoController::class, 'update']);
        Route::post('/upload_display-cases', [UploadDisplayCaseController::class, 'store']);
        Route::get('/display-cases', [GetDoctorDisplayCasesController::class, 'index']);
        Route::post('/display-cases/favorite', [UpdateDisplayCaseFavoriteController::class, 'update']);
        Route::delete('/delete-display-cases', [DeleteDisplayCaseController::class, 'destroy']);
        Route::post('/bookings/by-status', [GetBookingsByStatusController::class, 'index']);
        Route::post('/bookings/update-status', [UpdateBookingStatusController::class, 'update']);
        Route::post('/bookings/complete', [CompleteBookingController::class, 'complete']);
        Route::post('/panorama-teeth', [PanoramaTeethController::class, 'show']);
        Route::get('/today-approved', [TodayApprovedBookingsController::class, 'index']);
        Route::post('/teeth/update', [UpdateToothController::class, 'update']);
        Route::delete('/teeth/delete', [DeleteToothController::class, '__invoke']);
        Route::post('/teeth/store', [StoreToothController::class, '__invoke']);
        Route::post('/teeth/by-panorama', [GetTeethByPanoramaController::class, 'index']);

        // Doctor Panoramas & Teeth (AI-generated)
        Route::get('/panoramas', GetDoctorPanoramasController::class);
        Route::post('/teeth-doctor/store', CreateTeethDoctorController::class);
        Route::put('/teeth-doctor/update', UpdateTeethDoctorController::class);
        Route::delete('/teeth-doctor/delete', TeethDoctorController::class);
    });

});