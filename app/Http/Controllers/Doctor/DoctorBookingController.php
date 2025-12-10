<?php
namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\UpdateBookingStatusRequest;
use App\Services\doctor\DoctorBookingService;
use Illuminate\Http\JsonResponse;

class DoctorBookingController extends Controller
{
    protected $bookingService;

    public function __construct(DoctorBookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function listPendingAppointments(): JsonResponse
{
    return $this->bookingService->handlePendingAppointmentsResponse();
}

    public function listAcceptedAppointments(): JsonResponse
{
    return $this->bookingService->handleAcceptedAppointmentsResponse();
}

public function decideAppointment(UpdateBookingStatusRequest $request, $id): JsonResponse
{
    return $this->bookingService->handleAppointmentDecision($id, $request->status, $request->note);
}

public function listClientsWithDetails(): JsonResponse
{
    $clients = $this->bookingService->getDoctorClientsWithDetails();

    return response()->json($clients);
}
}