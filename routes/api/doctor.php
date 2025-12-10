<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorInfoController;
use App\Http\Controllers\Doctor\DoctorBookingController;

Route::prefix('doctor')->group(function (): void {
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('/info', [DoctorInfoController::class, 'submitInfo']);

        // مواعيد بانتظار القرار
        Route::get('/appointments/pending', [DoctorBookingController::class, 'listPendingAppointments']);
        Route::post('/appointments/{id}/decision', [DoctorBookingController::class, 'decideAppointment']);

        // مواعيد مقبولة (للهوم بيج)
        Route::get('/appointments/accepted', [DoctorBookingController::class, 'listAcceptedAppointments']);

        Route::post('/info/update', [DoctorInfoController::class, 'update']);
        Route::get('/clients', [DoctorBookingController::class, 'listClientsWithDetails']);

    });
});