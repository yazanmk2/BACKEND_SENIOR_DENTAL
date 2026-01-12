<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DeleteDisplayCaseRequest;
use App\Services\Doctor\DeleteDisplayCaseService;
use Illuminate\Http\JsonResponse;

class DeleteDisplayCaseController extends Controller
{
    protected DeleteDisplayCaseService $service;

    public function __construct(DeleteDisplayCaseService $service)
    {
        $this->service = $service;
    }

    public function destroy(DeleteDisplayCaseRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
