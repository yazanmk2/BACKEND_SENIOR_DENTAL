<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DeleteTeethDoctorService
{
    public function handle($request): array
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
               2️⃣ Find tooth in teeth_doctor
               AND make sure it belongs to doctor
            =============================== */
            $tooth = TeethDoctor::where('id', $request->id)
                ->whereHas('panoramaDoctor', function ($q) use ($doctor) {
                    $q->where('d_id', $doctor->id);
                })
                ->first();

            if (!$tooth) {
                return [
                    'status' => false,
                    'code' => 403,
                    'message' => 'Tooth not found or not authorized.',
                ];
            }

            /* ===============================
               3️⃣ Delete tooth_doctor record
            =============================== */
            $tooth->delete();

            return [
                'status' => true,
                'message' => 'Tooth deleted successfully.',
            ];

        } catch (Throwable $e) {
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Failed to delete tooth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
