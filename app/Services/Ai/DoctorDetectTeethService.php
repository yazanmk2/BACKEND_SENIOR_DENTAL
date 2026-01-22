<?php

namespace App\Services\Ai;

use App\Models\Doctor;
use App\Models\PanoramaPhotoDoctor;
use App\Models\TeethDoctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DoctorDetectTeethService
{
    private string $aiUrl = 'https://6165eec04a55.ngrok-free.app/detect_teeth';

    public function handle($image, string $customerName): array
    {
        try {

            /* ===============================
               1️⃣ Get doctor from token
            =============================== */
            $doctor = Doctor::where('u_id', Auth::id())->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor not found.',
                ];
            }

            $baseUrl = request()->getSchemeAndHttpHost();

            /* ===============================
               2️⃣ Store panorama photo
            =============================== */
            $imageName = 'doctor_panorama_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $storedPath = $image->storeAs(
                'panorama_photos_doctors',
                $imageName,
                'public'
            );

            $photoUrl = $baseUrl . '/storage/' . $storedPath;

            /* ===============================
               3️⃣ Save panorama record
            =============================== */
            $panorama = PanoramaPhotoDoctor::create([
                'd_id'          => $doctor->id,
                'photo'         => $photoUrl,
                'customer_name' => $customerName,
            ]);

            /* ===============================
               4️⃣ Send to Flask AI
            =============================== */
            $response = Http::timeout(180)
                ->attach(
                    'image',
                    file_get_contents(storage_path('app/public/' . $storedPath)),
                    $imageName
                )
                ->post($this->aiUrl);

            if (!$response->successful()) {
                return [
                    'status' => false,
                    'message' => 'AI service failed.',
                    'error' => $response->body(),
                ];
            }

            $aiData = $response->json();

            if (!isset($aiData['detections'])) {
                return [
                    'status' => false,
                    'message' => 'Invalid AI response.',
                ];
            }

            /* ===============================
               5️⃣ Store teeth
            =============================== */
            $aiBaseUrl =
                parse_url($this->aiUrl, PHP_URL_SCHEME) . '://' .
                parse_url($this->aiUrl, PHP_URL_HOST);

            $storedTeeth = [];

            foreach ($aiData['detections'] as $item) {

                $labelParts = explode('_', $item['label']);
                if (count($labelParts) < 2) continue;

                $quarter = (int) $labelParts[0];
                $toothInQuarter = (int) $labelParts[1];
                $condition = $labelParts[2] ?? null;

                $toothNumber = (($quarter - 1) * 8) + $toothInQuarter;

                /* === Download crop === */
                $encodedPath = implode(
                    '/',
                    array_map('rawurlencode', explode('/', $item['crop_url']))
                );

                $cropContent = file_get_contents($aiBaseUrl . $encodedPath);

                $cropName = 'tooth_' . $quarter . '_' . $toothInQuarter . '_' . uniqid() . '.png';
                $cropPath = 'teeth_doctors/panorama_' . $panorama->id . '/' . $cropName;

                Storage::disk('public')->put($cropPath, $cropContent);

                $cropUrl = $baseUrl . '/storage/' . $cropPath;

                $tooth = TeethDoctor::create([
                    'p_id' => $panorama->id,
                    'name' => "{$quarter}_{$toothInQuarter}",
                    'descripe' => $condition,
                    'number' => $toothNumber,
                    'photo_panorama_generated' => $cropUrl,
                ]);

                $storedTeeth[] = $tooth;
            }

            /* ===============================
               6️⃣ Response
            =============================== */
            return [
                'status' => true,
                'message' => 'Doctor panorama analyzed successfully.',
                'data' => [
                    'panorama_id' => $panorama->id,
                    'photo' => $photoUrl,
                    'customer_name' => $customerName,
                    'teeth_count' => count($storedTeeth),
                    'teeth' => $storedTeeth,
                ],
            ];

        } catch (Throwable $e) {

            return [
                'status' => false,
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
