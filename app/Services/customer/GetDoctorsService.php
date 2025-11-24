<?php

namespace App\Services\customer;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GetDoctorsService
{
    public function getAllDoctors()
    {
        $user = Auth::user();

        // Check customer has coordinates
        if (!$user->address || !str_contains($user->address, ',')) {
            return [
                'error' => true,
                'message' => 'Customer address does not contain valid coordinates.'
            ];
        }

        list($custLat, $custLng) = explode(',', $user->address);

        // Load local location file
        $locationMap = json_decode(
            Storage::get('location_map.json'),
            true
        );

        $doctors = Doctor::with('user')->get();

        foreach ($doctors as $doctor) {

            if (!$doctor->user->address || !str_contains($doctor->user->address, ',')) {
                $doctor->distance_km = null;
                $doctor->location_details = null;
                continue;
            }

            list($docLat, $docLng) = explode(',', $doctor->user->address);

            // Calculate distance
            $doctor->distance_km = $this->calculateDistance(
                floatval($custLat),
                floatval($custLng),
                floatval($docLat),
                floatval($docLng)
            );

            // Try retrieving cached location
            $doctor->location_details =
                $locationMap[$doctor->user->address] ?? [
                    "street"        => null,
                    "neighbourhood" => null,
                    "city"          => null,
                    "region"        => null,
                    "country"       => null
                ];
        }

        return $doctors;
    }

    // Distance formula
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);

        $a = sin($latDiff / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDiff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
