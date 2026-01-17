<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\GetDoctorBookingsByStatusRequest;
use App\Services\Doctor\GetDoctorBookingsByStatusService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetBookingsByStatusController extends Controller
{
    protected GetDoctorBookingsByStatusService $service;

    public function __construct(GetDoctorBookingsByStatusService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/bookings/by-status",
        summary: "Get Bookings by Status",
        description: "Retrieve doctor's bookings filtered by status",
        tags: ["Doctor"],
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
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function index(GetDoctorBookingsByStatusRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->status);

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
