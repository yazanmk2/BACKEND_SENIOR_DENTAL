<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Services\Auth\UpdateProfileService;
use Illuminate\Http\JsonResponse;
use Exception;

class UpdateProfileController extends Controller
{
    protected $service;

    public function __construct(UpdateProfileService $service)
    {
        $this->service = $service;
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $response = $this->service->update($request->validated());

            return response()->json($response, 200);

        } catch (Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
