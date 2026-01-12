<?php

namespace App\Services\Doctor;

use App\Models\Booking;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class GetDoctorBookingsByStatusService
{
    public function handle(string $status): array
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

            // 🔥 Get bookings with customer + user info
            $bookings = Booking::query()
                ->where('bookings.d_id', $doctor->id)
                ->where('bookings.status', $status)
                ->join('customers', 'bookings.c_id', '=', 'customers.id')
                ->join('users', 'customers.u_id', '=', 'users.id')
                ->select([
                    'bookings.id as booking_id',
                    'bookings.date',
                    'bookings.time',
                    'bookings.status',
                    'bookings.note',

                    'customers.id as customer_id',
                    'customers.birthdate',
                    'customers.patient_record',

                    'users.id as user_id',
                    'users.first_name',
                    'users.last_name',
                    'users.phone',
                    'users.email',
                    'users.gender',
                    'users.address',
                ])
                ->orderBy('bookings.date', 'asc')
                ->orderBy('bookings.time', 'asc')
                ->get();

            return [
                'status' => true,
                'message' => 'Bookings retrieved successfully.',
                'data' => $bookings
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to retrieve bookings.',
                'error' => $e->getMessage()
            ];
        }
    }
}
