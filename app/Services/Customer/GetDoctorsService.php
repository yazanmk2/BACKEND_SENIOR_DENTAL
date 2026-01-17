<?php

namespace App\Services\Customer;

use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GetDoctorsService
{
    public function getAllDoctors()
    {
        $user = Auth::user();

        // Extract coordinates from new address format
        $customerCoords = $this->extractCoordinates($user->address);

        if (!$customerCoords) {
            return [
                'status' => false,
                'message' => 'Customer address does not contain valid coordinates.'
            ];
        }

        list($custLat, $custLng) = $customerCoords;

        // Load the location map
        $locationMap = json_decode(Storage::get('location_map.json'), true);

        $doctors = Doctor::with('user')->get();

        foreach ($doctors as $doctor) {

            $doctorCoords = $this->extractCoordinates($doctor->user->address);

            if (!$doctorCoords) {
                $doctor->distance_km = null;
                $doctor->location_details = null;
                continue;
            }

            list($docLat, $docLng) = $doctorCoords;

            // Distance
            $doctor->distance_km = $this->calculateDistance(
                $custLat, $custLng, $docLat, $docLng
            );

            // Location details (from your custom json)
            
        }

        return $doctors;
    }

    // Extract coordinates from new address structure
 private function extractCoordinates($address)
{
    if (!$address) return null;

    // 1. Remove escape slashes and quotes
    $address = str_replace(['\"', '"'], '', $address);

    // 2. Remove unwanted "address:" or "address": labels
    $address = str_replace(['address:', 'address'], '', $address);

    // 3. Trim whitespace
    $address = trim($address);

    // 4. Split from the pipe | (only first part is coordinates)
    $parts = explode('|', $address);

    // 5. Clean the first part (coordinates only)
    $coords = trim($parts[0]);

    // 6. Ensure it contains a comma
    if (!strpos($coords, ',')) {
        return null;
    }

    // 7. Extract lat, lng
    list($lat, $lng) = explode(',', $coords);

    // 8. Clean them
    $lat = trim($lat);
    $lng = trim($lng);

    // 9. Validate numeric format
    if (!is_numeric($lat) || !is_numeric($lng)) {
        return null;
    }

    return [floatval($lat), floatval($lng)];
}



    // Haversine formula
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
