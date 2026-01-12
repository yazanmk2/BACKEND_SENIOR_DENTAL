<?php

namespace App\Services\Doctor;

use App\Models\Booking;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class CompleteBookingService
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

            // Get doctor from token
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

            // Prevent double completion
            if ($booking->status === 'completed') {
                return [
                    'status' => false,
                    'message' => 'Booking is already completed.'
                ];
            }

            // Update booking
            $booking->status = 'completed';
            $booking->note   = $data['note'] ?? null;
            $booking->save();

            return [
                'status' => true,
                'message' => 'Booking marked as completed.',
                'data' => $booking
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to complete booking.',
                'error' => $e->getMessage()
            ];
        }
    }
}
