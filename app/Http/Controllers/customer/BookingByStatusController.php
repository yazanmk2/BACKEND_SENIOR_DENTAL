<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\BookingByStatusRequest;
use App\Services\customer\BookingByStatusService;
use Illuminate\Http\JsonResponse;

class BookingByStatusController extends Controller
{
    protected $service;

    public function __construct(BookingByStatusService $service)
    {
        $this->service = $service;
    }

    public function getBookings(BookingByStatusRequest $request): JsonResponse
    {
        try {
            // Read status from BODY
            $status = $request->status;

            // Call the service
            $response = $this->service->getBookingsByStatus($status);

            // If service returned "status = false", still return 200 with meaningful message
            if ($response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                    'data' => $response['data'] ?? null,
                    'error' => $response['error'] ?? null,
                ], 200);
            }

            // Success
            return response()->json([
                'status' => true,
                'message' => 'Bookings retrieved successfully.',
                'data' => $response['data']
            ], 200);

        } catch (\Throwable $e) {

            // Any unexpected error is caught here
            return response()->json([
                'status' => false,
                'message' => 'Unexpected server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
