<?php
namespace App\Services\doctor;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DoctorBookingService
{
    public function getPendingAppointments()
    {
        $doctorId = Auth::user()->doctor->id;

        return Booking::with('customer')
            ->where('d_id', $doctorId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAcceptedAppointments($limit = null)
    {
        $doctorId = Auth::user()->doctor->id;

        $query = Booking::with('customer')
            ->where('d_id', $doctorId)
            ->where('status', 'accepted')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

   public function updateAppointmentStatus($bookingId, $status, $note = null)
{
    $doctorId = Auth::user()->doctor->id;

    $booking = Booking::where('id', $bookingId)
        ->where('d_id', $doctorId)
        ->whereIn('status', ['pending', 'accepted']) // نسمح بالتحديث من accepted كمان
        ->firstOrFail();

    $booking->status = $status;

    if ($status === 'done' && $note) {
        $booking->note = $note;
    }

    $booking->save();

    return $booking->load('customer');
}

    public function handlePendingAppointmentsResponse(): JsonResponse
{
    $appointments = $this->getPendingAppointments();

    if ($appointments->isEmpty()) {
        return response()->json([
            'message' => 'لا يوجد مواعيد معلّقة حالياً.'
        ], 200);
    }

    return response()->json($appointments);
}


public function handleAcceptedAppointmentsResponse(): JsonResponse
{
    $limit = request()->query('limit');
    $appointments = $this->getAcceptedAppointments($limit);

    if ($appointments->isEmpty()) {
        return response()->json([
            'message' => 'No appointments yet'
        ], 200);
    }

    $filtered = $appointments->map(function ($booking) {
        return [
            'customer_name' => $booking->customer->name,
            'date' => $booking->date,
            'time' => $booking->time,
        ];
    });

    return response()->json($filtered);
}

public function handleAppointmentDecision($bookingId, $status, $note = null): JsonResponse
{
    $booking = $this->updateAppointmentStatus($bookingId, $status, $note);

    return response()->json([
        'message' => 'Appointment status updated successfully.',
        'appointment' => $booking
    ]);
}

public function getDoctorClientsWithDetails()
{
    $doctorId = Auth::user()->doctor->id;

    $bookings = Booking::with(['customer.user', 'displayCases'])
        ->where('d_id', $doctorId)
        ->whereIn('status', ['accepted', 'done'])
        ->orderBy('date', 'desc')
        ->get();

    return $bookings->map(function ($booking) {
        return [
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'date' => $booking->date,
            'time' => $booking->time,
            'notes' => $booking->note,
            'customer' => [
                'name' => $booking->customer->user->first_name . ' ' . $booking->customer->user->last_name,
                'email' => $booking->customer->user->email,
                'phone' => $booking->customer->user->phone,
                'birthdate' => $booking->customer->birthdate,
                'patient_record' => $booking->customer->patient_record,
            ],
            'display_cases' => $booking->displayCases->map(function ($case) {
                return [
                    'photo_before' => asset('storage/' . $case->photo_before),
                    'photo_after' => asset('storage/' . $case->photo_after),
                    'favorite' => $case->favorite_flag,
                ];
            }),
        ];
    });
}
}