<?php

namespace App\Services\Ai;

use App\Models\Customer;
use App\Models\OrthodonticsResult;
use App\Models\PanoramaPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Throwable;

class DiagnoseOrthodonticsService
{
    /**
     * Flask AI endpoint
     */
    private string $aiUrl = 'https://6165eec04a55.ngrok-free.app/diagnose_ortho';

    public function handle($image): array
    {
        try {

            /* ===============================
               1️⃣ Get authenticated customer
            =============================== */
            $customer = Customer::where('u_id', Auth::id())->first();

            if (!$customer) {
                return [
                    'status' => false,
                    'message' => 'Customer not found',
                ];
            }

            /* ===============================
               2️⃣ Store panorama photo
            =============================== */
            $baseUrl = request()->getSchemeAndHttpHost();

            $imageName = 'ortho_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $storedPath = $image->storeAs(
                'panorama_photos',
                $imageName,
                'public'
            );

            $panoramaUrl = $baseUrl . '/storage/' . $storedPath;

            /* ===============================
               3️⃣ Save panorama (FIXED c_id)
            =============================== */
            $panorama = PanoramaPhoto::create([
                'c_id'  => $customer->id,   // ✅ REQUIRED
                'photo' => $panoramaUrl,
            ]);

            /* ===============================
               4️⃣ Send image to Flask AI
            =============================== */
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
                    'error' => $response->body(),
                ];
            }

            $aiData = $response->json();

            if (!isset($aiData['upper'], $aiData['lower'], $aiData['final'])) {
                return [
                    'status' => false,
                    'message' => 'Invalid AI response format.',
                ];
            }

            /* ===============================
               5️⃣ Save orthodontics result
            =============================== */
            $result = OrthodonticsResult::create([
                'p_id'  => $panorama->id,
                'upper' => $aiData['upper'],
                'lower' => $aiData['lower'],
                'final' => $aiData['final'],
            ]);

            /* ===============================
               6️⃣ Final response
            =============================== */
            return [
                'status' => true,
                'message' => 'Orthodontic diagnosis completed.',
                'data' => [
                    'panorama_id' => $panorama->id,
                    'panorama_photo' => $panoramaUrl,
                    'upper' => $result->upper,
                    'lower' => $result->lower,
                    'final' => $result->final,
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
