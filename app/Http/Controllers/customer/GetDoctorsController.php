<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\GetDoctorsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class GetDoctorsController extends Controller
{
    protected $service;

    public function __construct(GetDoctorsService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: "/v1/customer/doctors",
        summary: "Get All Doctors",
        description: "Retrieve a list of all available doctors",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Doctors retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Doctors retrieved successfully."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "specialization", type: "string"),
                                new OA\Property(property: "average_rate", type: "number")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function index(): JsonResponse
    {
        try {
            $doctors = $this->service->getAllDoctors();

            return response()->json([
                'status' => true,
                'message' => 'Doctors retrieved successfully.',
                'data' => $doctors
            ], 200);

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
