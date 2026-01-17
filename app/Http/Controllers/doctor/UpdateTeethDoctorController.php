<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateTeethDoctorRequest;
use App\Services\Doctor\UpdateTeethDoctorService;
use Illuminate\Http\JsonResponse;

class UpdateTeethDoctorController extends Controller
{
    public function __invoke(
        UpdateTeethDoctorRequest $request,
        UpdateTeethDoctorService $service
    ): JsonResponse {
        $result = $service->handle($request->validated());

        return response()->json(
            $result,
            $result['status'] ? 200 : 404
        );
    }
}
