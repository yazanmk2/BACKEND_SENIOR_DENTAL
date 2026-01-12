<?php

namespace App\Services\ai;

use App\Models\Customer;
use App\Models\PanoramaPhoto;
use App\Models\Teeth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class DetectTeethService
{
    private string $aiUrl = 'https://7d53c69ce90e.ngrok-free.app/detect_teeth';

    public function handle($image): array
    {
        try {
            /* ===============================
               1️⃣ Auth customer
            =============================== */
            $user = Auth::user();
            $customer = Customer::where('u_id', $user->id)->first();

            if (!$customer) {
                return [
                    'status' => false,
                    'message' => 'Customer not found.'
                ];
            }

            $baseUrl = request()->getSchemeAndHttpHost();

            /* ===============================
               2️⃣ Store panorama locally
            =============================== */
            $imageName = 'panorama_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $storedPath = $image->storeAs(
                'panorama_photos',
                $imageName,
                'public'
            );

            $fullImagePath = storage_path('app/public/' . $storedPath);

            /* ===============================
               3️⃣ RESIZE + COMPRESS (CRITICAL)
            =============================== */
            Image::make($fullImagePath)
                ->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($fullImagePath, 85);

            $panoramaUrl = $baseUrl . '/storage/' . $storedPath;

            /* ===============================
               4️⃣ Save panorama in DB
            =============================== */
            $panorama = PanoramaPhoto::create([
                'c_id'  => $customer->id,
                'photo' => $panoramaUrl
            ]);

            /* ===============================
               5️⃣ Send image to Flask AI
            =============================== */
            $response = Http::timeout(180)
                ->retry(2, 2000)
                ->attach(
                    'image',
                    file_get_contents($fullImagePath),
                    $imageName
                )
                ->post($this->aiUrl);

            if (!$response->successful()) {
                return [
                    'status' => false,
                    'message' => 'AI service failed.',
                    'error' => $response->body()
                ];
            }

            $aiData = $response->json();

            if (!isset($aiData['detections'])) {
                return [
                    'status' => false,
                    'message' => 'Invalid AI response.'
                ];
            }

            /* ===============================
               6️⃣ AI base URL
            =============================== */
            $aiBaseUrl =
                parse_url($this->aiUrl, PHP_URL_SCHEME) . '://' .
                parse_url($this->aiUrl, PHP_URL_HOST);

            /* ===============================
               7️⃣ Store teeth + crops
            =============================== */
            $storedTeeth = [];

            foreach ($aiData['detections'] as $item) {

                $labelParts = explode('_', $item['label']);
                if (count($labelParts) < 2) continue;

                $quarter = (int) $labelParts[0];
                $toothInQuarter = (int) $labelParts[1];
                $condition = $labelParts[2] ?? null;

                $toothNumber = (($quarter - 1) * 8) + $toothInQuarter;

                /* === SAFE URL ENCODING === */
                $relativePath = $item['crop_url'];
                $encodedPath = implode('/', array_map('rawurlencode', explode('/', $relativePath)));
                $cropFullUrl = $aiBaseUrl . $encodedPath;

                $cropContent = file_get_contents($cropFullUrl);

                $cropName = 'tooth_' . $quarter . '_' . $toothInQuarter . '_' . uniqid() . '.png';
                $cropStoragePath = 'teeth_crops/panorama_' . $panorama->id . '/' . $cropName;

                Storage::disk('public')->put($cropStoragePath, $cropContent);

                $cropPublicUrl = $baseUrl . '/storage/' . $cropStoragePath;

                $tooth = Teeth::create([
                    'p_id' => $panorama->id,
                    'name' => "{$quarter}_{$toothInQuarter}",
                    'photo_panorama_generated' => $cropPublicUrl,
                    'photo_icon' => null,
                    'descripe' => $condition,
                    'number' => $toothNumber,
                    'confidence' => $item['confidence'],
                ]);

                $storedTeeth[] = [
                    'id' => $tooth->id,
                    'name' => $tooth->name,
                    'number' => $tooth->number,
                    'condition' => $tooth->descripe,
                    'confidence' => $tooth->confidence,
                    'box' => $item['box'],
                    'photo_panorama_generated' => $cropPublicUrl
                ];
            }

            /* ===============================
               8️⃣ Response
            =============================== */
            return [
                'status' => true,
                'message' => 'Panorama analyzed successfully.',
                'data' => [
                    'panorama_id' => $panorama->id,
                    'panorama_photo' => $panoramaUrl,
                    'num_teeth' => count($storedTeeth),
                    'teeth' => $storedTeeth
                ]
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ];
        }
    }
}
