<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\DoctorDetectTeethRequest;
use App\Services\Ai\DoctorDetectTeethService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DoctorDetectTeethController extends Controller
{
    public function detect(
        DoctorDetectTeethRequest $request,
        DoctorDetectTeethService $service
    ): JsonResponse {
        try {

            $result = $service->handle(
                $request->file('photo'),
                $request->customer_name
            );

            return response()->json($result, 200);

        } catch (Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Controller error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
