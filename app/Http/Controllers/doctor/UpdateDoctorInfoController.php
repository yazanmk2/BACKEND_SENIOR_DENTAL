<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDoctorInfoRequest;
use App\Services\Doctor\UpdateDoctorInfoService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateDoctorInfoController extends Controller
{
    protected $service;

    public function __construct(UpdateDoctorInfoService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/profile",
        summary: "Update Doctor Profile",
        description: "Update the doctor's profile information",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "specialization", type: "string", example: "Orthodontist"),
                    new OA\Property(property: "bio", type: "string", example: "Experienced dental surgeon"),
                    new OA\Property(property: "experience_years", type: "integer", example: 10),
                    new OA\Property(property: "clinic_address", type: "string", example: "123 Medical Center")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function update(UpdateDoctorInfoRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
