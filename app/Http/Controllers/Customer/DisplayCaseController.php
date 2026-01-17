<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\DisplayCaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;
use OpenApi\Attributes as OA;

class DisplayCaseController extends Controller
{
    protected $service;

    public function __construct(DisplayCaseService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: "/v1/customer/favorite-cases",
        summary: "Get Favorite Display Cases",
        description: "Retrieve favorite display cases for the customer",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Favorite display cases retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function getFavoriteCases(): JsonResponse
    {
        try {
            $cases = $this->service->getFavoriteCases();

            return response()->json([
                'status' => true,
                'message' => 'Favorite display cases retrieved successfully',
                'data' => $cases,
            ], 200);

        } catch (QueryException $e) {
            Log::error("Database error in getFavoriteCases: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'A database error occurred.',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {
            Log::error("Unexpected error in getFavoriteCases: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while retrieving display cases.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
