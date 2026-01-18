<?php

namespace App\Services\Ai;

use App\Models\Customer;
use App\Models\PanoramaPhoto;
use App\Models\Teeth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DetectTeethService
{
    /**
     * Flask AI endpoint
     */
    private string $aiUrl = 'https://d9260da76906.ngrok-free.app/detect_teeth';

    public function handle($image): array
    {
        try {

            /* ===============================
               1️⃣ Get authenticated customer
            =============================== */
            $user = Auth::user();
            $customer = Customer::where('u_id', $user->id)->first();

            if (!$customer) {
                return [
                    'status' => false,
                    'message' => 'Customer not found.'
                ];
            }

            /* ===============================
               2️⃣ Base URL (dynamic)
            =============================== */
            $baseUrl = request()->getSchemeAndHttpHost();

            /* ===============================
               3️⃣ Store panorama image
            =============================== */
            $imageName = 'panorama_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $storedPath = $image->storeAs(
                'panorama_photos',
                $imageName,
                'public'
            );

            $panoramaUrl = $baseUrl . '/storage/' . $storedPath;

            /* ===============================
               4️⃣ Save panorama in DB
            =============================== */
            $panorama = PanoramaPhoto::create([
                'c_id'  => $customer->id,
                'photo' => $panoramaUrl
            ]);

            $response = Http::timeout(180)
                ->retry(2, 2000)
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
               6️⃣ Extract AI base URL
            =============================== */
            $aiBaseUrl =
                parse_url($this->aiUrl, PHP_URL_SCHEME)
                . '://' .
                parse_url($this->aiUrl, PHP_URL_HOST);

            /* ===============================
               7️⃣ Store teeth + crops
            =============================== */
            $storedTeeth = [];

            foreach ($aiData['detections'] as $item) {

                /* ---------- Parse label ---------- */
                // Example: "4_6_Caries"
                $labelParts = explode('_', $item['label']);
                if (count($labelParts) < 2) {
                    continue;
                }

                $quarter        = (int) $labelParts[0];
                $toothInQuarter = (int) $labelParts[1];
                $condition      = $labelParts[2] ?? null;

                // Universal tooth number (1–32)
                $toothNumber = (($quarter - 1) * 8) + $toothInQuarter;

                /* ---------- SAFE crop URL ---------- */
                $relativePath = ltrim($item['crop_url'], '/');

                $encodedPath = implode(
                    '/',
                    array_map('rawurlencode', explode('/', $relativePath))
                );

                $cropFullUrl = $aiBaseUrl . '/' . $encodedPath;

                /* ---------- Download crop safely ---------- */
                $cropResponse = Http::timeout(30)
                    ->retry(3, 1000)
                    ->get($cropFullUrl);

                // If crop missing → skip tooth safely
                if (!$cropResponse->successful()) {
                    continue;
                }

                $cropContent = $cropResponse->body();

                /* ---------- Store crop in Laravel ---------- */
                $cropName = 'tooth_' . $quarter . '_' . $toothInQuarter . '_' . uniqid() . '.png';

                $cropStoragePath =
                    'teeth_crops/panorama_' . $panorama->id . '/' . $cropName;

                Storage::disk('public')->put($cropStoragePath, $cropContent);

                $cropPublicUrl = $baseUrl . '/storage/' . $cropStoragePath;

                /* ---------- Save tooth ---------- */
                $tooth = Teeth::create([
                    'p_id' => $panorama->id,
                    'name' => "{$quarter}_{$toothInQuarter}",
                    'photo_panorama_generated' => $cropPublicUrl,
                    'photo_icon' => null,
                    'descripe' => $condition,
                    'number' => $toothNumber
                ]);

                $storedTeeth[] = [
                    'id' => $tooth->id,
                    'name' => $tooth->name,
                    'number' => $tooth->number,
                    'condition' => $tooth->descripe,
                    'box' => $item['box'],
                    'photo_panorama_generated' => $cropPublicUrl
                ];
            }

            /* ===============================
               8️⃣ Final response
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
