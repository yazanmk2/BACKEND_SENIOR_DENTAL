<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerInfoRequest;
use App\Services\Customer\CustomerInfoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;
use OpenApi\Attributes as OA;

class CustomerInfoController extends Controller
{
    protected $service;

    public function __construct(CustomerInfoService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/customer/info",
        summary: "Update Customer Info",
        description: "Update customer profile information",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                    new OA\Property(property: "address", type: "string", example: "123 Main St"),
                    new OA\Property(property: "date_of_birth", type: "string", format: "date", example: "1990-01-01")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Customer info updated successfully",
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
    public function uploadInfo(CustomerInfoRequest $request): JsonResponse
    {
        try {
            $customer = $this->service->updateInfo($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Customer info updated successfully',
                'data' => $customer,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update customer info',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
