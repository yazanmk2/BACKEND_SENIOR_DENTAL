<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\CompleteBookingRequest;
use App\Services\Doctor\CompleteBookingService;
use Illuminate\Http\JsonResponse;

class CompleteBookingController extends Controller
{
    protected CompleteBookingService $service;

    public function __construct(CompleteBookingService $service)
    {
        $this->service = $service;
    }

    public function complete(CompleteBookingRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
