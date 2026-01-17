<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Services\Auth\UpdateProfileService;
use Illuminate\Http\JsonResponse;
use Exception;
use OpenApi\Attributes as OA;

class UpdateProfileController extends Controller
{
    protected $service;

    public function __construct(UpdateProfileService $service)
    {
        $this->service = $service;
    }

    #[OA\Post(
        path: "/v1/update-profile",
        summary: "Update Profile",
        description: "Update the user's profile information",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "phone", type: "string", example: "+1234567890")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
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
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $response = $this->service->update($request->validated());

            return response()->json($response, 200);

        } catch (Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
