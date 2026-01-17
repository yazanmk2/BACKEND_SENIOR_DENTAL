<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\TodayApprovedBookingsRequest;
use App\Services\Doctor\TodayApprovedBookingsService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Exception;
use OpenApi\Attributes as OA;

class TodayApprovedBookingsController extends Controller
{
    protected TodayApprovedBookingsService $service;

    public function __construct(TodayApprovedBookingsService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: "/v1/doctor/today-approved",
        summary: "Get Today's Approved Bookings",
        description: "Retrieve all approved bookings for today",
        tags: ["Doctor"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Today's approved bookings",
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
    public function index(TodayApprovedBookingsRequest $request): JsonResponse
    {
        try {
            $response = $this->service->handle();

            return response()->json(
                $response,
                $response['status'] ? 200 : 403
            );

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
