<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class HealthController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ) {}

    /**
     * Perform a comprehensive health check of all system components.
     */
    #[OA\Get(
        path: "/health",
        summary: "Health Check",
        description: "Perform a comprehensive health check of all system components",
        tags: ["Health"],
        responses: [
            new OA\Response(
                response: 200,
                description: "All systems healthy",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "healthy"),
                        new OA\Property(property: "timestamp", type: "string", example: "2026-01-17T08:00:00+00:00"),
                        new OA\Property(property: "version", type: "string", example: "1.0.0"),
                        new OA\Property(property: "environment", type: "string", example: "production"),
                        new OA\Property(property: "checks", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 503, description: "System degraded")
        ]
    )]
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'chatbot' => $this->checkChatbot(),
        ];

        $allHealthy = collect($checks)->every(
            fn($check) => $check['status'] === 'healthy'
        );

        $status = $allHealthy ? 'healthy' : 'degraded';
        $httpCode = $allHealthy ? 200 : 503;

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => $checks,
        ], $httpCode);
    }

    /**
     * Check database connectivity.
     */
    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'latency_ms' => $latency,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connectivity.
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            Cache::put($key, true, 10);
            $retrieved = Cache::get($key);
            Cache::forget($key);

            return [
                'status' => $retrieved === true ? 'healthy' : 'unhealthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage accessibility.
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'test');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);

            return [
                'status' => $exists ? 'healthy' : 'unhealthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check chatbot service health.
     */
    private function checkChatbot(): array
    {
        $health = $this->chatService->checkHealth();

        return [
            'status' => $health['chatbot'],
            'details' => $health['details'] ?? null,
            'error' => $health['error'] ?? null,
        ];
    }
}
