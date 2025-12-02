<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\CreateBookingRequest;
use App\Services\customer\CreateBookingService;
use Illuminate\Http\JsonResponse;

class CreateBookingController extends Controller
{
    protected CreateBookingService $service;

    public function __construct(CreateBookingService $service)
    {
        $this->service = $service;
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $response = $this->service->create($request->validated());

            // if service already returns status=false, just forward it
            return response()->json($response, 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
