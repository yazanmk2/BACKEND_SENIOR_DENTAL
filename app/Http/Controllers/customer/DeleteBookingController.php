<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\DeleteBookingRequest;
use App\Services\Customer\DeleteBookingService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class DeleteBookingController extends Controller
{
    protected $service;

    public function __construct(DeleteBookingService $service)
    {
        $this->service = $service;
    }

    #[OA\Delete(
        path: "/v1/customer/deletebookings",
        summary: "Delete Booking",
        description: "Delete a customer booking",
        tags: ["Customer"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["id"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1, description: "Booking ID to delete")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Bad request"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function delete(DeleteBookingRequest $request): JsonResponse
    {
        try {
            $bookingId = $request->id;
            $result = $this->service->deleteBooking($bookingId);

            if ($result['error']) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'status'  => true,
                'message' => $result['message']
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Database error occurred.',
                'error'   => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error occurred.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
