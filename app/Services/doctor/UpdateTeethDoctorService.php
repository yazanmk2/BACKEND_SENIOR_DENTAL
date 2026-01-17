<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Auth;

class UpdateTeethDoctorService
{
    public function handle(array $data): array
    {
        $doctor = Doctor::where('u_id', Auth::id())->first();

        if (!$doctor) {
            return [
                'status' => false,
                'message' => 'Doctor not found'
            ];
        }

        $tooth = TeethDoctor::find($data['id']);

        if (!$tooth) {
            return [
                'status' => false,
                'message' => 'Tooth not found'
            ];
        }

        $tooth->update([
            'descripe' => $data['descripe']
        ]);

        return [
            'status' => true,
            'message' => 'Tooth description updated successfully',
            'data' => $tooth
        ];
    }
}
