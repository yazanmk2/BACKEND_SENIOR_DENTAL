<?php

namespace App\Services\Doctor;

use App\Models\Booking;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class UpdateBookingStatusService
{
    public function handle(array $data): array
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.'
                ];
            }

            // Get doctor by token
            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // Get booking (must belong to doctor)
            $booking = Booking::where('id', $data['booking_id'])
                ->where('d_id', $doctor->id)
                ->first();

            if (!$booking) {
                return [
                    'status' => false,
                    'message' => 'Booking not found or not owned by this doctor.'
                ];
            }

            // Prevent re-updating completed bookings (optional but recommended)
            if ($booking->status === 'completed') {
                return [
                    'status' => false,
                    'message' => 'Completed booking cannot be modified.'
                ];
            }

            // Update status
            $booking->status = $data['status'];
            $booking->save();

            return [
                'status' => true,
                'message' => 'Booking status updated successfully.',
                'data' => $booking
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to update booking status.',
                'error' => $e->getMessage()
            ];
        }
    }
}
