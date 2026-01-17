<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DeleteTeethDoctorRequest;
use App\Services\Doctor\DeleteTeethDoctorService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TeethDoctorController extends Controller
{
    #[OA\Delete(
        path: "/v1/doctor/teeth-doctor/delete",
        summary: "Delete Tooth from Doctor Panorama",
        description: "Delete a tooth record from doctor's panorama",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["id"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tooth deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function __invoke(
        DeleteTeethDoctorRequest $request,
        DeleteTeethDoctorService $service
    ): JsonResponse {
        $result = $service->handle($request);

        return response()->json(
            $result,
            $result['status'] ? 200 : ($result['code'] ?? 500)
        );
    }
}
