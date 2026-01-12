<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UploadDisplayCaseRequest;
use App\Services\Doctor\UploadDisplayCaseService;
use Illuminate\Http\JsonResponse;

class UploadDisplayCaseController extends Controller
{
    protected $service;

    public function __construct(UploadDisplayCaseService $service)
    {
        $this->service = $service;
    }

    public function store(UploadDisplayCaseRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
