<?php

namespace App\Services\doctor;

use App\Models\Booking;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TodayApprovedBookingsService
{
    public function handle(): array
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.'
                ];
            }

            if ($user->type !== 'doctor') {
                return [
                    'status' => false,
                    'message' => 'Unauthorized. Doctor access only.'
                ];
            }

            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // ✅ today date (YYYY-MM-DD)
            $today = Carbon::today()->toDateString();

            // ✅ bookings today + approved + include customer + user
            $bookings = Booking::query()
                ->where('bookings.d_id', $doctor->id)
                ->where('bookings.status', 'approved')
                ->whereDate('bookings.date', $today)
                ->join('customers', 'bookings.c_id', '=', 'customers.id')
                ->join('users', 'customers.u_id', '=', 'users.id')
                ->select([
                    // booking
                    'bookings.id as booking_id',
                    'bookings.c_id',
                    'bookings.d_id',
                    'bookings.date',
                    'bookings.time',
                    'bookings.status',
                    'bookings.note',
                    'bookings.created_at',
                    'bookings.updated_at',

                    // customer
                    'customers.id as customer_id',
                    'customers.birthdate',
                    'customers.patient_record',

                    // user (customer info)
                    'users.id as user_id',
                    'users.first_name',
                    'users.father_name',
                    'users.last_name',
                    'users.phone',
                    'users.email',
                    'users.gender',
                    'users.address',
                    'users.photo',
                ])
                ->orderBy('bookings.time', 'asc')
                ->get();

            return [
                'status' => true,
                'message' => 'Today approved bookings retrieved successfully.',
                'data' => $bookings
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to retrieve today approved bookings.',
                'error' => $e->getMessage()
            ];
        }
    }
}
