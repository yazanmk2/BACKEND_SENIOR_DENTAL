<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\DiagnoseOrthodonticsRequest;
use App\Services\Ai\DiagnoseOrthodonticsService;
use Illuminate\Http\JsonResponse;
use Throwable;
use OpenApi\Attributes as OA;

class DiagnoseOrthodonticsController extends Controller
{
    #[OA\Post(
        path: "/v1/ai/diagnose-orthodontics",
        summary: "Diagnose Orthodontics",
        description: "Upload an image for AI-powered orthodontic diagnosis",
        tags: ["AI"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["image"],
                    properties: [
                        new OA\Property(property: "image", type: "string", format: "binary", description: "Dental image for orthodontic analysis")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Diagnosis completed successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Diagnosis failed"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function diagnose(
        DiagnoseOrthodonticsRequest $request,
        DiagnoseOrthodonticsService $service
    ): JsonResponse {
        try {
            $result = $service->handle(
                $request->file('image')
            );

            if ($result['status'] === false) {
                return response()->json($result, 400);
            }

            return response()->json($result, 200);

        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Controller error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
