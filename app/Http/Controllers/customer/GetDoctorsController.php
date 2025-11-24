<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Services\customer\GetDoctorsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class GetDoctorsController extends Controller
{
    protected $service;

    public function __construct(GetDoctorsService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $doctors = $this->service->getAllDoctors();

            return response()->json([
                'status' => true,
                'message' => 'Doctors retrieved successfully.',
                'data' => $doctors
            ], 200);

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
