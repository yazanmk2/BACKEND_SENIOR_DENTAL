<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\GetDoctorPanoramasService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetDoctorPanoramasController extends Controller
{
    #[OA\Get(
        path: "/v1/doctor/panoramas",
        summary: "Get Doctor Panoramas",
        description: "Get all panoramas for the authenticated doctor with their teeth",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Panoramas retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 404, description: "Doctor not found")
        ]
    )]
    public function __invoke(GetDoctorPanoramasService $service): JsonResponse
    {
        $result = $service->handle();

        return response()->json(
            $result,
            $result['status'] ? 200 : ($result['code'] ?? 404)
        );
    }
}
