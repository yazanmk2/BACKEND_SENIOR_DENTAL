<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreToothRequest;
use App\Services\Doctor\StoreToothService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StoreToothController extends Controller
{
    #[OA\Post(
        path: "/v1/doctor/teeth/store",
        summary: "Store New Tooth",
        description: "Create a new tooth record for a patient",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["panorama_id", "number", "name"],
                properties: [
                    new OA\Property(property: "panorama_id", type: "integer", example: 1),
                    new OA\Property(property: "number", type: "integer", example: 11),
                    new OA\Property(property: "name", type: "string", example: "Central Incisor"),
                    new OA\Property(property: "descripe", type: "string", example: "Upper right central incisor")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
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
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function __invoke(
        StoreToothRequest $request,
        StoreToothService $service
    ): JsonResponse {
        return response()->json(
            $service->handle($request)
        );
    }
}
