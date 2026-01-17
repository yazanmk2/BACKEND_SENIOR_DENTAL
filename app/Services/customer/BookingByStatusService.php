<?php

namespace App\Services\Customer;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingByStatusService
{
    public function getBookingsByStatus($status)
    {
        try {
            // 1. Get user ID from authenticated token
            $userId = Auth::id();

            // 2. Find the customer row connected to this user
            $customer = Customer::where('u_id', $userId)->first();

            if (!$customer) {
                return [
                    'status' => false,
                    'message' => 'Customer account not found.',
                    'data' => null
                ];
            }

            // 3. Fetch bookings by status for this customer
            $bookings = Booking::where('c_id', $customer->id)
                ->where('status', $status)
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->get();

            // 4. Attach ONLY doctor name + address
            foreach ($bookings as $booking) {
                $doctor = Doctor::find($booking->d_id);

                if ($doctor) {
                    $doctorUser = User::find($doctor->u_id);

                    if ($doctorUser) {
                        $booking->doctor_name = $doctorUser->first_name . ' ' . $doctorUser->last_name;
                        $booking->doctor_address = $doctorUser->address;
                    } else {
                        $booking->doctor_name = null;
                        $booking->doctor_address = null;
                    }
                } else {
                    $booking->doctor_name = null;
                    $booking->doctor_address = null;
                }
            }

            return [
                'status' => true,
                'message' => 'Bookings retrieved successfully.',
                'data' => $bookings
            ];

        } catch (\Exception $e) {

            return [
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ];
        }
    }
}
