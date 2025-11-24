<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApplicationRateRequest;
use App\Services\Auth\ApplicationRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class ApplicationRateController extends Controller
{
    protected $service;

    public function __construct(ApplicationRateService $service)
    {
        $this->service = $service;
    }

    public function submit(ApplicationRateRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->service->submitRate($validated);

            return response()->json([
                'status' => true,
                'message' => 'Feedback submitted successfully.',
                'data' => $result
            ], 201);

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
