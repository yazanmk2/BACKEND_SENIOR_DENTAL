<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\CreateTeethDoctorRequest;
use App\Services\Doctor\CreateTeethDoctorService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CreateTeethDoctorController extends Controller
{
    #[OA\Post(
        path: "/v1/doctor/teeth-doctor/store",
        summary: "Create Tooth for Doctor Panorama",
        description: "Create a new tooth record for a doctor's panorama",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["p_id", "name", "number"],
                    properties: [
                        new OA\Property(property: "p_id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "1_1"),
                        new OA\Property(property: "number", type: "integer", example: 1),
                        new OA\Property(property: "descripe", type: "string", example: "Caries"),
                        new OA\Property(property: "photo", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tooth created successfully",
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
        CreateTeethDoctorRequest $request,
        CreateTeethDoctorService $service
    ): JsonResponse {
        $result = $service->handle($request);

        return response()->json(
            $result,
            $result['status'] ? 201 : ($result['code'] ?? 500)
        );
    }
}
