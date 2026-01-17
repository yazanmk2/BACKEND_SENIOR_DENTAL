<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DeleteToothRequest;
use App\Services\Doctor\DeleteToothService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DeleteToothController extends Controller
{
    #[OA\Delete(
        path: "/v1/doctor/teeth/delete",
        summary: "Delete Tooth",
        description: "Delete a tooth record",
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
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function __invoke(
        DeleteToothRequest $request,
        DeleteToothService $service
    ): JsonResponse {
        return response()->json(
            $service->handle($request->id)
        );
    }
}
