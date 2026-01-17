<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\DeletePhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class DeletePhotoController extends Controller
{
    protected $service;

    public function __construct(DeletePhotoService $service)
    {
        $this->service = $service;
    }

    #[OA\Delete(
        path: "/v1/delete-photo",
        summary: "Delete Profile Photo",
        description: "Delete the user's profile photo",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Photo deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "deleted", type: "boolean")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function delete(): JsonResponse
    {
        try {
            $result = $this->service->deletePhoto();

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message'],
                'deleted' => $result['deleted'],
            ]);

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
