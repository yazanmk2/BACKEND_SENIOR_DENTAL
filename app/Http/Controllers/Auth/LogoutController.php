<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogoutRequest;
use App\Services\Auth\LogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;
use OpenApi\Attributes as OA;

class LogoutController extends Controller
{
    protected $logoutService;

    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    #[OA\Post(
        path: "/v1/logout",
        summary: "User Logout",
        description: "Logout the authenticated user and revoke their token",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Logout successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Logout successful. Token revoked.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "No authenticated user found"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function logout(LogoutRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'No authenticated user found.',
                ], 401);
            }

            $this->logoutService->logout($user);

            return response()->json([
                'status' => true,
                'message' => 'Logout successful. Token revoked.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong during logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
