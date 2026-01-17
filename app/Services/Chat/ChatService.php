<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.chatbot.url', 'http://localhost:8001');
        $this->timeout = config('services.chatbot.timeout', 60);
    }

    /**
     * Send a message to the AI chatbot service.
     */
    public function sendMessage(string $message, int $userId, ?string $conversationId = null): array
    {
        $requestId = Str::uuid()->toString();
        $conversationId = $conversationId ?? Str::uuid()->toString();

        Log::info('Sending message to chatbot', [
            'request_id' => $requestId,
            'user_id' => $userId,
            'conversation_id' => $conversationId,
            'message_length' => strlen($message)
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-Request-ID' => $requestId,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v1/chat", [
                    'message' => $message,
                    'conversation_id' => $conversationId,
                    'metadata' => [
                        'user_id' => $userId,
                        'source' => 'laravel_backend'
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Chatbot response received', [
                    'request_id' => $requestId,
                    'type' => $data['type'] ?? 'unknown',
                    'processing_time_ms' => $data['processing_time_ms'] ?? null
                ]);

                return [
                    'answer' => $data['message'] ?? '',
                    'type' => $data['type'] ?? 'answer',
                    'handoff_to_human' => ($data['type'] ?? 'answer') === 'handoff',
                    'handoff_reason' => $data['handoff_reason'] ?? null,
                    'citations' => $data['citations'] ?? [],
                    'confidence' => $data['retrieval']['top_similarity_score'] ?? null,
                    'conversation_id' => $conversationId,
                    'request_id' => $requestId,
                    'processing_time_ms' => $data['processing_time_ms'] ?? null
                ];
            }

            Log::error('Chatbot service returned error', [
                'request_id' => $requestId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->fallbackResponse($conversationId, $requestId, 'Service returned an error');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Chatbot service connection failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage()
            ]);

            return $this->fallbackResponse($conversationId, $requestId, 'Service unavailable');

        } catch (\Exception $e) {
            Log::error('Chatbot service exception', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->fallbackResponse($conversationId, $requestId, 'Unexpected error');
        }
    }

    /**
     * Check the health status of the chatbot service.
     */
    public function checkHealth(): array
    {
        $cacheKey = 'chatbot_health_check';

        // Cache health check for 30 seconds to avoid hammering the service
        return Cache::remember($cacheKey, 30, function () {
            try {
                $response = Http::timeout(5)
                    ->get("{$this->baseUrl}/api/v1/health/ready");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'chatbot' => $data['status'] === 'ready' ? 'healthy' : 'degraded',
                        'status_code' => $response->status(),
                        'details' => $data
                    ];
                }

                return [
                    'chatbot' => 'unhealthy',
                    'status_code' => $response->status(),
                    'error' => 'Service returned non-200 status'
                ];

            } catch (\Exception $e) {
                return [
                    'chatbot' => 'unreachable',
                    'status_code' => null,
                    'error' => $e->getMessage()
                ];
            }
        });
    }

    /**
     * Get chat history for a user (placeholder for future implementation).
     */
    public function getHistory(int $userId): array
    {
        // Future implementation: Store and retrieve chat history from database
        // For now, return empty array as history is not persisted
        return [
            'conversations' => [],
            'message' => 'Chat history not yet implemented'
        ];
    }

    /**
     * Generate a fallback response when the chatbot service is unavailable.
     */
    private function fallbackResponse(string $conversationId, string $requestId, string $reason): array
    {
        return [
            'answer' => 'I apologize, but I am temporarily unavailable. Please try again in a moment, or contact our support team for immediate assistance.',
            'type' => 'handoff',
            'handoff_to_human' => true,
            'handoff_reason' => "service_unavailable: {$reason}",
            'citations' => [],
            'confidence' => null,
            'conversation_id' => $conversationId,
            'request_id' => $requestId,
            'processing_time_ms' => null,
            'error' => true
        ];
    }
}
