<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateToothRequest;
use App\Services\Doctor\UpdateToothService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UpdateToothController extends Controller
{
    public function __construct(
        protected UpdateToothService $service
    ) {}

    public function update(UpdateToothRequest $request): JsonResponse
    {
        try {
            $tooth = $this->service->update($request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Tooth updated successfully.',
                'data'    => [
                    'id'        => $tooth->id,
                    'name'      => $tooth->name,
                    'number'    => $tooth->number,
                    'descripe'  => $tooth->descripe,
                    'photo'     => $tooth->photo_panorama_generated,
                ]
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Failed to update tooth.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
