<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateToothRequest;
use App\Services\Doctor\UpdateToothService;
use Illuminate\Http\JsonResponse;
use Throwable;
use OpenApi\Attributes as OA;

class UpdateToothController extends Controller
{
    public function __construct(
        protected UpdateToothService $service
    ) {}

    #[OA\Post(
        path: "/v1/doctor/teeth/update",
        summary: "Update Tooth",
        description: "Update an existing tooth record",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["id"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Central Incisor"),
                    new OA\Property(property: "number", type: "integer", example: 11),
                    new OA\Property(property: "descripe", type: "string", example: "Updated description")
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
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function update(UpdateToothRequest $request): JsonResponse
    {
        try {
            $tooth = $this->service->update($request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Tooth updated successfully.',
                'data'    => [
                    'id'        => $tooth->id,
                    'name'      => $tooth->name,
                    'number'    => $tooth->number,
                    'descripe'  => $tooth->descripe,
                    'photo'     => $tooth->photo_panorama_generated,
                ]
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update tooth.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
