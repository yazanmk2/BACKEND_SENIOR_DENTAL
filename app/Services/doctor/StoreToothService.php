<?php

namespace App\Services\Doctor;

use App\Models\Teeth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreToothService
{
    public function handle($request): array
    {
        try {
            $photoUrl = null;

            /* ===============================
               Upload photo (optional)
            =============================== */
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                $name = 'tooth_manual_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs(
                    'teeth_manual',
                    $name,
                    'public'
                );

                $photoUrl = request()->getSchemeAndHttpHost() . '/storage/' . $path;
            }

            /* ===============================
               Create tooth
            =============================== */
            $tooth = Teeth::create([
                'p_id' => $request->p_id,
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
                'message' => 'Failed to create tooth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
