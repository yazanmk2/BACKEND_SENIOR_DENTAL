<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GetDoctorPanoramasService
{
    public function handle(): array
    {
        try {
            /* ===============================
               1️⃣ Get doctor from token
            =============================== */
            $doctor = Doctor::where('u_id', Auth::id())->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'code' => 404,
                    'message' => 'Doctor not found.',
                ];
            }

            /* ===============================
               2️⃣ Get panoramas with teeth
            =============================== */
            $panoramas = $doctor->panoramaPhotos()
                ->with('teeth')
                ->latest()
                ->get();

            return [
                'status' => true,
                'message' => 'Doctor panoramas retrieved successfully.',
                'data' => $panoramas,
            ];

        } catch (Throwable $e) {
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Failed to retrieve panoramas.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
