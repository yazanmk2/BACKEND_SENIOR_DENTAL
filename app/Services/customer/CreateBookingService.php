<?php

namespace App\Services\customer;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CreateBookingService
{
    public function create($data)
    {
        try {
            // 1. get u_id from token
            $userId = Auth::id();

            // 2. find customer by u_id
            $customer = Customer::where('u_id', $userId)->first();

            if (!$customer) {
                return [
                    'status' => false,
                    'message' => 'Customer account not found.',
                    'data' => null
                ];
            }

            // 3. create booking
            $bookings = Bookings::create([
                'c_id'  => $customer->id,   // ← fetched from token
                'd_id'  => $data['d_id'],
                'date'  => $data['date'],
                'time'  => $data['time'],
                'status' => 'pending'
            ]);

            return [
                'status' => true,
                'message' => 'Booking created successfully.',
                'data' => $bookings
            ];

        } catch (\Exception $e) {

            return [
                'status' => false,
                'message' => 'Failed to create booking.',
                'error' => $e->getMessage()
            ];
        }
    }
}
