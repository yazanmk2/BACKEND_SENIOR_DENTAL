<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class GetDoctorPanoramasService
{
    public function handle(): array
    {
        $doctor = Doctor::where('u_id', Auth::id())->first();

        if (!$doctor) {
            return [
                'status' => false,
                'message' => 'Doctor not found'
            ];
        }

        $panoramas = $doctor->panoramaPhotos()
            ->with('teeth')
            ->latest()
            ->get();

        return [
            'status' => true,
            'message' => 'Doctor panoramas retrieved successfully',
            'data' => $panoramas
        ];
    }
}
