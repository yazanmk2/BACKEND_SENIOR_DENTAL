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
use App\Http\Controllers\doctor\PanoramaTeethController;
use App\Http\Controllers\doctor\TodayApprovedBookingsController;



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



});

});