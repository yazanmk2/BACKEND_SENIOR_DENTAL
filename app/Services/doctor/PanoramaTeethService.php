<?php

namespace App\Services\doctor;

use App\Models\Customer;
use App\Models\PanoramaPhoto;
use App\Models\Teeth;
use Illuminate\Support\Facades\Auth;

class PanoramaTeethService
{
    public function getLatestPanoramaWithTeeth(int $customerId)
    {
        $doctorUser = Auth::user();

        if ($doctorUser->type !== 'doctor') {
            return [
                'error' => true,
                'message' => 'Unauthorized. Doctor access only.'
            ];
        }

        // Load customer WITH user info
        $customer = Customer::with('user')->find($customerId);

        if (!$customer) {
            return [
                'error' => true,
                'message' => 'Customer not found.'
            ];
        }

        // Prepare customer info
        $customerInfo = [
            'customer_id'     => $customer->id,
            'birthdate'       => $customer->birthdate,
            'patient_record'  => $customer->patient_record,
            'first_name'      => $customer->user->first_name ?? null,
            'last_name'       => $customer->user->last_name ?? null,
            'phone'           => $customer->user->phone ?? null,
            'email'           => $customer->user->email ?? null,
            'gender'          => $customer->user->gender ?? null,
            'address'         => $customer->user->address ?? null,
        ];

        // Get latest panorama photo
        $panorama = PanoramaPhoto::where('c_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$panorama) {
            return [
                'error' => false,
                'data' => [
                    'customer' => $customerInfo,
                    'panorama_photo' => 'no photo uploaded yet',
                    'teeth' => []
                ]
            ];
        }

        // Get teeth for panorama
        $teeth = Teeth::where('p_id', $panorama->id)->get();

        return [
            'error' => false,
            'data' => [
                'customer' => $customerInfo,
                'panorama_photo' => $panorama,
                'teeth' => $teeth
            ]
        ];
    }
}
