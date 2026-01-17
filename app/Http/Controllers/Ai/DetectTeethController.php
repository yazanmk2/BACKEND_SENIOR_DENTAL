<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\DetectTeethRequest;
use App\Services\Ai\DetectTeethService;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class DetectTeethController extends Controller
{
    protected DetectTeethService $service;

    public function __construct(DetectTeethService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/ai/detect-teeth",
        summary: "Detect Teeth in Image",
        description: "Upload a panoramic X-ray image to detect and analyze teeth using AI",
        tags: ["AI"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["image"],
                    properties: [
                        new OA\Property(property: "image", type: "string", format: "binary", description: "Panoramic X-ray image")
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
            new OA\Response(response: 400, description: "Detection failed"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function detect(DetectTeethRequest $request)
    {
        try {
            $result = $this->service->handle($request->file('image'));

            return response()->json(
                $result,
                $result['status'] ? 200 : 400
            );

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
