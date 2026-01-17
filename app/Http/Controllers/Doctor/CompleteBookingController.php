<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\CompleteBookingRequest;
use App\Services\Doctor\CompleteBookingService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CompleteBookingController extends Controller
{
    protected CompleteBookingService $service;

    public function __construct(CompleteBookingService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/bookings/complete",
        summary: "Complete Booking",
        description: "Mark a booking as completed",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["booking_id"],
                properties: [
                    new OA\Property(property: "booking_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking completed",
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
    public function complete(CompleteBookingRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
