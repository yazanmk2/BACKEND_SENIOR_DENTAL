<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\TodayApprovedBookingsRequest;
use App\Services\doctor\TodayApprovedBookingsService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Exception;

class TodayApprovedBookingsController extends Controller
{
    protected TodayApprovedBookingsService $service;

    public function __construct(TodayApprovedBookingsService $service)
    {
        $this->service = $service;
    }

    public function index(TodayApprovedBookingsRequest $request): JsonResponse
    {
        try {
            $response = $this->service->handle();

            return response()->json(
                $response,
                $response['status'] ? 200 : 403
            );

        } catch (QueryException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Unexpected server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
