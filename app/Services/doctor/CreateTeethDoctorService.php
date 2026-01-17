<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\PanoramaPhotoDoctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Storage;

class CreateTeethDoctorService
{
    public function handle(array $data, int $userId): array
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
           2️⃣ Check panorama belongs to doctor
        =============================== */
        $panorama = PanoramaPhotoDoctor::where('id', $data['p_id'])
            ->where('d_id', $doctor->id)
            ->first();

        if (!$panorama) {
            return [
                'status' => false,
                'code' => 403,
                'message' => 'Panorama not found or not authorized',
            ];
        }

        /* ===============================
           3️⃣ Handle optional photo
        =============================== */
        $photoUrl = null;

        if (isset($data['photo'])) {
            $photoName = 'tooth_' . time() . '_' . uniqid() . '.' . $data['photo']->getClientOriginalExtension();

            $path = $data['photo']->storeAs(
                'teeth_doctors/panorama_' . $panorama->id,
                $photoName,
                'public'
            );

            $photoUrl = request()->getSchemeAndHttpHost() . '/storage/' . $path;
        }

        /* ===============================
           4️⃣ Create teeth_doctor record
        =============================== */
        $tooth = TeethDoctor::create([
            'p_id' => $panorama->id,
            'name' => $data['name'],
            'number' => $data['number'],
            'descripe' => $data['descripe'] ?? null,
            'photo_panorama_generated' => $photoUrl,
        ]);

        return [
            'status' => true,
            'code' => 201,
            'message' => 'Tooth created successfully',
            'data' => $tooth,
        ];
    }
}
