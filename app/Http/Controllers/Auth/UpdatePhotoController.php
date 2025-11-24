<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePhotoRequest;
use App\Services\Auth\UpdatePhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class UpdatePhotoController extends Controller
{
    protected $service;

    public function __construct(UpdatePhotoService $service)
    {
        $this->service = $service;
    }

    public function update(UpdatePhotoRequest $request): JsonResponse
    {
        try {
            $file = $request->file('photo');

            $photoUrl = $this->service->updatePhoto($file);

            return response()->json([
                'status' => true,
                'message' => 'Photo updated successfully.',
                'photo_url' => $photoUrl,
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
