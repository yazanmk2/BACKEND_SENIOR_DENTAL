<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\DoctorDetectTeethRequest;
use App\Services\Ai\DoctorDetectTeethService;
use Illuminate\Http\JsonResponse;
use Throwable;
use OpenApi\Attributes as OA;

class DoctorDetectTeethController extends Controller
{
    #[OA\Post(
        path: "/v1/ai/doctor/detect-teeth",
        summary: "Doctor Detect Teeth",
        description: "Doctor uploads a panoramic image to detect teeth for a specific customer",
        tags: ["AI"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["photo", "customer_name"],
                    properties: [
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Panoramic X-ray image"),
                        new OA\Property(property: "customer_name", type: "string", description: "Customer name")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Teeth detected successfully",
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
    public function detect(
        DoctorDetectTeethRequest $request,
        DoctorDetectTeethService $service
    ): JsonResponse {
        try {
            $result = $service->handle(
                $request->file('photo'),
                $request->customer_name
            );

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
