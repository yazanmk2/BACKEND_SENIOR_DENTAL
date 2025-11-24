<?php

namespace App\Services\customer;

use App\Models\PanoramaPhoto;
use App\Models\Teeth;
use Illuminate\Support\Facades\Auth;
use Exception;

class TeethService
{
    public function getLatestTeeth()
    {
        $user = Auth::user();
        $customer = $user->customer ?? null;

        if (! $customer) {
            return [
                'status' => false,
                'message' => 'Customer account not found.',
                'data' => null
            ];
        }

        // get latest panorama photo
        $latestPhoto = PanoramaPhoto::where('c_id', $customer->id)
            ->latest()
            ->first();

        if (! $latestPhoto) {
            return [
                'status' => true,
                'message' => 'no photo uploaded',
                'data' => null
            ];
        }

        // get teeth linked with this photo
        $teeth = Teeth::where('p_id', $latestPhoto->id)->get();

        return [
            'status' => true,
            'message' => 'Teeth data retrieved successfully',
            'data' => [
                'photo' => $latestPhoto->photo,
                'teeth' => $teeth
            ]
        ];
    }
}
