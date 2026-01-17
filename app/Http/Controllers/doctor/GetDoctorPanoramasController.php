<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\GetDoctorPanoramasService;
use Illuminate\Http\JsonResponse;

class GetDoctorPanoramasController extends Controller
{
    public function __invoke(GetDoctorPanoramasService $service): JsonResponse
    {
        $result = $service->handle();

        return response()->json(
            $result,
            $result['status'] ? 200 : 404
        );
    }
}
