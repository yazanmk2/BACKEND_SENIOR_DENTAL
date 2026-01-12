<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDoctorInfoRequest;
use App\Services\Doctor\UpdateDoctorInfoService;
use Illuminate\Http\JsonResponse;

class UpdateDoctorInfoController extends Controller
{
    protected $service;

    public function __construct(UpdateDoctorInfoService $service)
    {
        $this->service = $service;
    }

    public function update(UpdateDoctorInfoRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
