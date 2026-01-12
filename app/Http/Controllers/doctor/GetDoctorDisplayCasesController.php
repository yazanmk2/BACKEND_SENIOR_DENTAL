<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\GetDoctorDisplayCasesService;
use Illuminate\Http\JsonResponse;

class GetDoctorDisplayCasesController extends Controller
{
    protected GetDoctorDisplayCasesService $service;

    public function __construct(GetDoctorDisplayCasesService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $response = $this->service->handle();

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
