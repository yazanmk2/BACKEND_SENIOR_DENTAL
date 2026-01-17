<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\PanoramaPhotoDoctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CreateTeethDoctorService
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
               2️⃣ Check panorama belongs to doctor
            =============================== */
            $panorama = PanoramaPhotoDoctor::where('id', $request->p_id)
                ->where('d_id', $doctor->id)
                ->first();

            if (!$panorama) {
                return [
                    'status' => false,
                    'code' => 403,
                    'message' => 'Panorama not found or not authorized.',
                ];
            }

            /* ===============================
               3️⃣ Handle optional photo
            =============================== */
            $photoUrl = null;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                $photoName = 'tooth_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs(
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
                'name' => $request->name,
                'number' => $request->number,
                'descripe' => $request->descripe,
                'photo_panorama_generated' => $photoUrl,
            ]);

            return [
                'status' => true,
                'message' => 'Tooth created successfully.',
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
                'message' => 'Failed to create tooth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
