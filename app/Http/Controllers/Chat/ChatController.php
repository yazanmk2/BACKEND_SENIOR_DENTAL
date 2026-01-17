<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ChatMessageRequest;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Exception;
use OpenApi\Attributes as OA;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ) {}

    #[OA\Post(
        path: "/v1/chat/message",
        summary: "Send Chat Message",
        description: "Send a message to the AI dental chatbot",
        tags: ["Chat"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["message"],
                properties: [
                    new OA\Property(property: "message", type: "string", example: "What is a root canal?"),
                    new OA\Property(property: "conversation_id", type: "string", nullable: true, example: "conv_123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Chat response",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "response", type: "string"),
                            new OA\Property(property: "conversation_id", type: "string")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Chat service error")
        ]
    )]
    public function sendMessage(ChatMessageRequest $request): JsonResponse
    {
        try {
            $result = $this->chatService->sendMessage(
                message: $request->validated('message'),
                userId: auth()->id(),
                conversationId: $request->validated('conversation_id')
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chat service error.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    #[OA\Get(
        path: "/v1/chat/history",
        summary: "Get Chat History",
        description: "Retrieve chat history for the authenticated user",
        tags: ["Chat"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Chat history",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function getHistory(): JsonResponse
    {
        try {
            $history = $this->chatService->getHistory(auth()->id());

            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve chat history.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    #[OA\Get(
        path: "/v1/chat/health",
        summary: "Chat Service Health",
        description: "Check the health status of the AI chatbot service",
        tags: ["Chat"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Service healthy",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 503, description: "Service unavailable")
        ]
    )]
    public function health(): JsonResponse
    {
        $status = $this->chatService->checkHealth();

        $httpCode = $status['chatbot'] === 'healthy' ? 200 : 503;

        return response()->json([
            'success' => $status['chatbot'] === 'healthy',
            'data' => $status
        ], $httpCode);
    }
}
