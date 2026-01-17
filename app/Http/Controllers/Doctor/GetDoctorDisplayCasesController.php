<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\GetDoctorDisplayCasesService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GetDoctorDisplayCasesController extends Controller
{
    protected GetDoctorDisplayCasesService $service;

    public function __construct(GetDoctorDisplayCasesService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: "/v1/doctor/display-cases",
        summary: "Get Doctor Display Cases",
        description: "Retrieve all display cases for the authenticated doctor",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display cases retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function index(): JsonResponse
    {
        $response = $this->service->handle();

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
