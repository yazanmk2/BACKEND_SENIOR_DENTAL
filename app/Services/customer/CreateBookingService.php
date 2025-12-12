<?php

namespace App\Services\customer;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CreateBookingService
{
    public function create(array $data): array
    {
        try {
            // 1. get logged in user id from token
            $userId = Auth::id();

            if (!$userId) {
                return [
                    'status'  => false,
                    'message' => 'Unauthenticated.',
                    'data'    => null,
                ];
            }

            // 2. find customer connected to this user
            $customer = Customer::where('u_id', $userId)->first();

            if (!$customer) {
                return [
                    'status'  => false,
                    'message' => 'Customer account not found.',
                    'data'    => null,
                ];
            }

            // 3. create booking (status always pending)
            $booking = Booking::create([
                'c_id'   => $customer->id,
                'd_id'   => $data['d_id'],
                'date'   => $data['date'],
                'time'   => $data['time'],
                'status' => 'pending',
            ]);

            return [
                'status'  => true,
                'message' => 'Booking created successfully.',
                'data'    => $booking,
            ];

        } catch (\Throwable $e) {

            return [
                'status'  => false,
                'message' => 'Failed to create booking.',
                'error'   => $e->getMessage(),
            ];
        }
    }
}
