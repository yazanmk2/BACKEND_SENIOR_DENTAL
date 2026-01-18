<?php

namespace App\Services\Doctor;

use App\Models\Teeth;
use Throwable;

class GetTeethByPanoramaService
{
    public function handle(int $pId): array
    {
        try {
            $teeth = Teeth::where('p_id', $pId)
                ->orderBy('number')
                ->get();

            return [
                'status' => true,
                'data' => $teeth,
            ];

        } catch (Throwable $e) {
            return [
                'status' => false,
                'message' => 'Failed to retrieve teeth.',
                'error' => $e->getMessage(),
            ];
        }
    }
}
