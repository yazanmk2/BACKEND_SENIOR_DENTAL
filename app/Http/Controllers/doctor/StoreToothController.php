<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreToothRequest;
use App\Services\Doctor\StoreToothService;
use Illuminate\Http\JsonResponse;

class StoreToothController extends Controller
{
    public function __invoke(
        StoreToothRequest $request,
        StoreToothService $service
    ): JsonResponse {
        return response()->json(
            $service->handle($request)
        );
    }
}
