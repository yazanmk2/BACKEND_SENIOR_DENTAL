<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\PanoramaTeethRequest;
use App\Services\Doctor\PanoramaTeethService;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class PanoramaTeethController extends Controller
{
    protected $service;

    public function __construct(PanoramaTeethService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/doctor/panorama-teeth",
        summary: "Get Panorama with Teeth",
        description: "Retrieve the latest panorama image with teeth data for a customer",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["customer_id"],
                properties: [
                    new OA\Property(property: "customer_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Panorama and teeth retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function show(PanoramaTeethRequest $request)
    {
        try {
            $result = $this->service->getLatestPanoramaWithTeeth(
                $request->customer_id
            );

            if (isset($result['error']) && $result['error']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'data' => null
                ], 403);
            }

            return response()->json([
                'status' => true,
                'message' => 'Panorama and teeth retrieved successfully.',
                'data' => $result['data']
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
                'message' => 'Unexpected server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
