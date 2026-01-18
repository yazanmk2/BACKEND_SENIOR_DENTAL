<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateDoctorInfoService
{
    public function handle(array $data)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ];
            }

            // Find or create doctor record (firstOrNew doesn't save until we call save())
            $doctor = Doctor::firstOrNew(
                ['u_id' => $user->id],
                ['average_rate' => 0]
            );

            // Handle CV upload
            if (isset($data['cv'])) {
                $cvPath = $data['cv']->store('doctor-cv', 'public');
                $doctor->cv = $cvPath;
            }

            // Update other fields
            $doctor->specialization  = $data['specialization'];
            $doctor->previous_works = $data['previous_works'] ?? null;
            $doctor->open_time       = $data['open_time'];
            $doctor->close_time      = $data['close_time'];

            $doctor->save();

            // Update user address if provided (for location/coordinates)
            if (isset($data['address'])) {
                $user->address = $data['address'];
                $user->save();
            }

            return [
                'status' => true,
                'message' => 'Doctor information saved successfully.',
                'data' => $doctor
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => 'Failed to save doctor information.',
                'error' => $e->getMessage()
            ];
        }
    }
}
