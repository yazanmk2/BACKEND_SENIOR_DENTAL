<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customer\CustomerInfoController;
use App\Http\Controllers\customer\DisplayCaseController;
use App\Http\Controllers\customer\TeethController;
use App\Http\Controllers\customer\GetDoctorsController;
use App\Http\Controllers\customer\BookingByStatusController;
use App\Http\Controllers\customer\CreateBookingController;
use App\Http\Controllers\customer\DeleteBookingController;



Route::prefix('customer')->middleware('auth:sanctum')->group(function () {
    Route::post('/info', [CustomerInfoController::class, 'uploadInfo']);
    Route::get('/favorite-cases', [DisplayCaseController::class, 'getFavoriteCases']);
    Route::get('/teeth', [TeethController::class, 'getTeeth']);
    Route::get('/doctors', [GetDoctorsController::class, 'index']);
    Route::post('/bookings', [BookingByStatusController::class, 'getBookings']);
    Route::post('/createbookings', [CreateBookingController::class, 'store']);
    Route::delete('/deletebookings', [DeleteBookingController::class, 'delete']);


});
