<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\DeletePhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Exception;

class DeletePhotoController extends Controller
{
    protected $service;

    public function __construct(DeletePhotoService $service)
    {
        $this->service = $service;
    }

    public function delete(): JsonResponse
    {
        try {
            $result = $this->service->deletePhoto();

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message'],
                'deleted' => $result['deleted'],
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
