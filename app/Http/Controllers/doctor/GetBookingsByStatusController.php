<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\GetDoctorBookingsByStatusRequest;
use App\Services\Doctor\GetDoctorBookingsByStatusService;
use Illuminate\Http\JsonResponse;

class GetBookingsByStatusController extends Controller
{
    protected GetDoctorBookingsByStatusService $service;

    public function __construct(GetDoctorBookingsByStatusService $service)
    {
        $this->service = $service;
    }

    public function index(GetDoctorBookingsByStatusRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->status);

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
