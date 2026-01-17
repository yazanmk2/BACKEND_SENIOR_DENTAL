<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UpdateTeethDoctorService
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
               2️⃣ Find tooth AND verify ownership
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
               3️⃣ Update tooth
            =============================== */
            $tooth->update([
                'descripe' => $request->descripe
            ]);

            return [
                'status' => true,
                'message' => 'Tooth description updated successfully.',
                'data' => [
                    'id' => $tooth->id,
                    'p_id' => $tooth->p_id,
                    'name' => $tooth->name,
                    'number' => $tooth->number,
                    'descripe' => $tooth->descripe,
                    'photo' => $tooth->photo_panorama_generated,
                ],
            ];

        } catch (Throwable $e) {
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Failed to update tooth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
