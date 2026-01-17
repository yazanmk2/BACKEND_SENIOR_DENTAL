<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\TeethDoctor;

class DeleteTeethDoctorService
{
    public function handle(int $toothId, int $userId): array
    {
        /* ===============================
           1️⃣ Get doctor from token
        =============================== */
        $doctor = Doctor::where('u_id', $userId)->first();

        if (!$doctor) {
            return [
                'status' => false,
                'code' => 404,
                'message' => 'Doctor not found',
            ];
        }

        /* ===============================
           2️⃣ Find tooth in teeth_doctor
           AND make sure it belongs to doctor
        =============================== */
        $tooth = TeethDoctor::where('id', $toothId)
            ->whereHas('panoramaDoctor', function ($q) use ($doctor) {
                $q->where('d_id', $doctor->id);
            })
            ->first();

        if (!$tooth) {
            return [
                'status' => false,
                'code' => 403,
                'message' => 'Tooth not found or not authorized',
            ];
        }

        /* ===============================
           3️⃣ Delete tooth_doctor record
        =============================== */
        $tooth->delete();

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tooth deleted successfully',
        ];
    }
}
