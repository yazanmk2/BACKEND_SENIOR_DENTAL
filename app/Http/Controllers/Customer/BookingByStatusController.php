<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\BookingByStatusRequest;
use App\Services\Customer\BookingByStatusService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookingByStatusController extends Controller
{
    protected $service;

    public function __construct(BookingByStatusService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/customer/bookings",
        summary: "Get Customer Bookings by Status",
        description: "Retrieve customer's bookings filtered by status",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status"],
                properties: [
                    new OA\Property(property: "status", type: "string", enum: ["pending", "approved", "rejected", "completed"], example: "pending")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Bookings retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function getBookings(BookingByStatusRequest $request): JsonResponse
    {
        try {
            $status = $request->status;
            $response = $this->service->getBookingsByStatus($status);

            if ($response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                    'data' => $response['data'] ?? null,
                    'error' => $response['error'] ?? null,
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Bookings retrieved successfully.',
                'data' => $response['data']
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
