<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDisplayCaseFavoriteRequest;
use App\Services\Doctor\UpdateDisplayCaseFavoriteService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateDisplayCaseFavoriteController extends Controller
{
    protected UpdateDisplayCaseFavoriteService $service;

    public function __construct(UpdateDisplayCaseFavoriteService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/display-cases/favorite",
        summary: "Toggle Display Case Favorite",
        description: "Mark or unmark a display case as favorite",
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
                description: "Favorite status updated",
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
    public function update(UpdateDisplayCaseFavoriteRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
