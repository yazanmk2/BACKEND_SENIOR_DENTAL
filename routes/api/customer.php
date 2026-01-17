<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerInfoController;
use App\Http\Controllers\Customer\DisplayCaseController;
use App\Http\Controllers\Customer\TeethController;
use App\Http\Controllers\Customer\GetDoctorsController;
use App\Http\Controllers\Customer\BookingByStatusController;
use App\Http\Controllers\Customer\CreateBookingController;
use App\Http\Controllers\Customer\DeleteBookingController;



Route::prefix('customer')->middleware('auth:sanctum')->group(function () {
    Route::post('/info', [CustomerInfoController::class, 'uploadInfo']);
    Route::get('/favorite-cases', [DisplayCaseController::class, 'getFavoriteCases']);
    Route::get('/teeth', [TeethController::class, 'getTeeth']);
    Route::get('/doctors', [GetDoctorsController::class, 'index']);
    Route::post('/bookings', [BookingByStatusController::class, 'getBookings']);
    Route::post('/createbookings', [CreateBookingController::class, 'store']);
    Route::delete('/deletebookings', [DeleteBookingController::class, 'delete']);


});
