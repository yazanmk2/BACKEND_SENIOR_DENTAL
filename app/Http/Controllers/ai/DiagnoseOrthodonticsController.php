<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\DiagnoseOrthodonticsRequest;
use App\Services\Ai\DiagnoseOrthodonticsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DiagnoseOrthodonticsController extends Controller
{
    /**
     * Diagnose orthodontics using AI
     *
     * @param DiagnoseOrthodonticsRequest $request
     * @param DiagnoseOrthodonticsService $service
     * @return JsonResponse
     */
    public function diagnose(
        DiagnoseOrthodonticsRequest $request,
        DiagnoseOrthodonticsService $service
    ): JsonResponse {
        try {

            $result = $service->handle(
                $request->file('image')
            );

            // Service already returns structured response
            if ($result['status'] === false) {
                return response()->json($result, 400);
            }

            return response()->json($result, 200);

        } catch (Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Controller error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
