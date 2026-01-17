<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DeleteDisplayCaseRequest;
use App\Services\Doctor\DeleteDisplayCaseService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DeleteDisplayCaseController extends Controller
{
    protected DeleteDisplayCaseService $service;

    public function __construct(DeleteDisplayCaseService $service)
    {
        $this->service = $service;
    }

    #[OA\Delete(
        path: "/v1/doctor/delete-display-cases",
        summary: "Delete Display Case",
        description: "Delete a display case",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["display_case_id"],
                properties: [
                    new OA\Property(property: "display_case_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Display case deleted",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function destroy(DeleteDisplayCaseRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
