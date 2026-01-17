<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateBookingStatusRequest;
use App\Services\Doctor\UpdateBookingStatusService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateBookingStatusController extends Controller
{
    protected UpdateBookingStatusService $service;

    public function __construct(UpdateBookingStatusService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/bookings/update-status",
        summary: "Update Booking Status",
        description: "Update the status of a booking (approve/reject)",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["booking_id", "status"],
                properties: [
                    new OA\Property(property: "booking_id", type: "integer", example: 1),
                    new OA\Property(property: "status", type: "string", enum: ["approved", "rejected"], example: "approved")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking status updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function update(UpdateBookingStatusRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
