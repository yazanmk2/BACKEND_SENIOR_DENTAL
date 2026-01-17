<?php

namespace App\Services\Doctor;

use App\Models\Teeth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DeleteToothService
{
    public function handle(int $toothId): array
    {
        try {
            $tooth = Teeth::findOrFail($toothId);

            /* ===============================
               Delete photo if exists
            =============================== */
            if ($tooth->photo_panorama_generated) {
                $path = str_replace(
                    url('/storage') . '/',
                    '',
                    $tooth->photo_panorama_generated
                );

                Storage::disk('public')->delete($path);
            }

            /* ===============================
               Delete record
            =============================== */
            $tooth->delete();

            return [
                'status' => true,
                'message' => 'Tooth deleted successfully.',
            ];

        } catch (Throwable $e) {
            return [
                'status' => false,
                'message' => 'Failed to delete tooth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
