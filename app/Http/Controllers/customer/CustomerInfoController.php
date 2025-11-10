<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\CustomerInfoRequest;
use App\Services\customer\CustomerInfoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class CustomerInfoController extends Controller
{
    protected $service;

    public function __construct(CustomerInfoService $service)
    {
        $this->service = $service;
    }

    public function uploadInfo(CustomerInfoRequest $request): JsonResponse
    {
        try {
            $customer = $this->service->updateInfo($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Customer info updated successfully',
                'data' => $customer,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update customer info',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
