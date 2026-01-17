<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApplicationRateRequest;
use App\Services\Auth\ApplicationRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class ApplicationRateController extends Controller
{
    protected $service;

    public function __construct(ApplicationRateService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/rate-app",
        summary: "Rate Application",
        description: "Submit a rating and feedback for the application",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["rating"],
                properties: [
                    new OA\Property(property: "rating", type: "integer", minimum: 1, maximum: 5, example: 5),
                    new OA\Property(property: "feedback", type: "string", nullable: true, example: "Great app!")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Feedback submitted successfully",
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
    public function submit(ApplicationRateRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->service->submitRate($validated);

            return response()->json([
                'status' => true,
                'message' => 'Feedback submitted successfully.',
                'data' => $result
            ], 201);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
