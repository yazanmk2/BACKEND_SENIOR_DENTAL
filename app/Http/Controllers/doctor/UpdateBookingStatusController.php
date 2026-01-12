<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateBookingStatusRequest;
use App\Services\Doctor\UpdateBookingStatusService;
use Illuminate\Http\JsonResponse;

class UpdateBookingStatusController extends Controller
{
    protected UpdateBookingStatusService $service;

    public function __construct(UpdateBookingStatusService $service)
    {
        $this->service = $service;
    }

    public function update(UpdateBookingStatusRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
