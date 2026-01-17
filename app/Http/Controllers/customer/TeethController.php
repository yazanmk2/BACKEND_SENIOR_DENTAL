<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\TeethService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class TeethController extends Controller
{
    protected $service;

    public function __construct(TeethService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: "/v1/customer/teeth",
        summary: "Get Customer Teeth Data",
        description: "Retrieve the latest teeth data for the customer",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Teeth data retrieved successfully",
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
    public function getTeeth(): JsonResponse
    {
        try {
            $result = $this->service->getLatestTeeth();

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => $result['data']
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while retrieving teeth data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
