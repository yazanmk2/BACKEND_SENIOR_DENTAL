<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Services\customer\DisplayCaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;

class DisplayCaseController extends Controller
{
    protected $service;

    public function __construct(DisplayCaseService $service)
    {
        $this->service = $service;
    }

    public function getFavoriteCases(): JsonResponse
    {
        try {
            // Main Logic
            $cases = $this->service->getFavoriteCases();

            return response()->json([
                'status' => true,
                'message' => 'Favorite display cases retrieved successfully',
                'data' => $cases,
            ], 200);

        } catch (QueryException $e) {

            // Database-level error
            Log::error("Database error in getFavoriteCases: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'A database error occurred.',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {

            // Any unexpected error
            Log::error("Unexpected error in getFavoriteCases: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while retrieving display cases.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
