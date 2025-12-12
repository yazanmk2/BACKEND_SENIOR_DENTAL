<?php

namespace App\Services\customer;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DeleteBookingService
{
    public function deleteBooking($id)
    {
        // 1. Find booking
        $booking = Booking::find($id);

        if (!$booking) {
            return [
                'error' => true,
                'message' => 'Booking not found.'
            ];
        }

        // 2. Verify that the logged-in user is the owner
        $user = Auth::user();
        $customer = $user->customer;

        if (!$customer) {
            return [
                'error' => true,
                'message' => 'Customer profile not found.'
            ];
        }

        if ($booking->c_id !== $customer->id) {
            return [
                'error' => true,
                'message' => 'Unauthorized: You can only delete your own bookings.'
            ];
        }

        // 3. Delete booking
        $booking->delete();

        return [
            'error' => false,
            'message' => 'Booking deleted successfully.'
        ];
    }
}
