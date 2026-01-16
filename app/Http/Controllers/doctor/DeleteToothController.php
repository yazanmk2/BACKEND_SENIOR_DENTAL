<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DeleteToothRequest;
use App\Services\Doctor\DeleteToothService;
use Illuminate\Http\JsonResponse;

class DeleteToothController extends Controller
{
    public function __invoke(
        DeleteToothRequest $request,
        DeleteToothService $service
    ): JsonResponse {
        return response()->json(
            $service->handle($request->id)
        );
    }
}
