<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UploadDisplayCaseRequest;
use App\Services\Doctor\UploadDisplayCaseService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UploadDisplayCaseController extends Controller
{
    protected $service;

    public function __construct(UploadDisplayCaseService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/upload_display-cases",
        summary: "Upload Display Case",
        description: "Upload a new display case with before/after images",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["before_image", "after_image"],
                    properties: [
                        new OA\Property(property: "before_image", type: "string", format: "binary"),
                        new OA\Property(property: "after_image", type: "string", format: "binary"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "treatment_type", type: "string")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Display case uploaded successfully",
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
    public function store(UploadDisplayCaseRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
