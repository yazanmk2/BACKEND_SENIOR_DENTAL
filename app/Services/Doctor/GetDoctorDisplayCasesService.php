<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\DisplayCase;
use Illuminate\Support\Facades\Auth;

class GetDoctorDisplayCasesService
{
    public function handle(): array
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.'
                ];
            }

            // Get doctor by token user_id
            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // Fetch all display cases for this doctor
            $cases = DisplayCase::where('d_id', $doctor->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'status' => true,
                'message' => 'Display cases retrieved successfully.',
                'data' => $cases
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to retrieve display cases.',
                'error' => $e->getMessage()
            ];
        }
    }
}
