<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateBookingRequest;
use App\Services\Customer\CreateBookingService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CreateBookingController extends Controller
{
    protected CreateBookingService $service;

    public function __construct(CreateBookingService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/customer/createbookings",
        summary: "Create Booking",
        description: "Create a new appointment booking with a doctor",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["doctor_id", "date", "time"],
                properties: [
                    new OA\Property(property: "doctor_id", type: "integer", example: 1),
                    new OA\Property(property: "date", type: "string", format: "date", example: "2026-01-20"),
                    new OA\Property(property: "time", type: "string", example: "10:00"),
                    new OA\Property(property: "notes", type: "string", nullable: true, example: "First visit")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $response = $this->service->create($request->validated());

            return response()->json($response, 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
