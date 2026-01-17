<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateTeethDoctorRequest;
use App\Services\Doctor\UpdateTeethDoctorService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateTeethDoctorController extends Controller
{
    #[OA\Put(
        path: "/v1/doctor/teeth-doctor/update",
        summary: "Update Tooth Description",
        description: "Update the description of a tooth in doctor's panorama",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["id", "descripe"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "descripe", type: "string", example: "Caries detected")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tooth updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function __invoke(
        UpdateTeethDoctorRequest $request,
        UpdateTeethDoctorService $service
    ): JsonResponse {
        $result = $service->handle($request);

        return response()->json(
            $result,
            $result['status'] ? 200 : ($result['code'] ?? 404)
        );
    }
}
