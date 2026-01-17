<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePhotoRequest;
use App\Services\Auth\UpdatePhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;
use OpenApi\Attributes as OA;

class UpdatePhotoController extends Controller
{
    protected $service;

    public function __construct(UpdatePhotoService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/update-photo",
        summary: "Update Profile Photo",
        description: "Upload or update the user's profile photo",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["photo"],
                    properties: [
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Profile photo file")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Photo updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "photo_url", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function update(UpdatePhotoRequest $request): JsonResponse
    {
        try {
            $file = $request->file('photo');

            $photoUrl = $this->service->updatePhoto($file);

            return response()->json([
                'status' => true,
                'message' => 'Photo updated successfully.',
                'photo_url' => $photoUrl,
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
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
