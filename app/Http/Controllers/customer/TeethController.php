<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Services\customer\TeethService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class TeethController extends Controller
{
    protected $service;

    public function __construct(TeethService $service)
    {
        $this->service = $service;
    }

    public function getTeeth(): JsonResponse
    {
        try {
            $result = $this->service->getLatestTeeth();

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => $result['data']
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
                'message' => 'Something went wrong while retrieving teeth data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
