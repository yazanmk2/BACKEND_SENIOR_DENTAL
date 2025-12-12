<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\DeleteBookingRequest;
use App\Services\customer\DeleteBookingService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Database\QueryException;

class DeleteBookingController extends Controller
{
    protected $service;

    public function __construct(DeleteBookingService $service)
    {
        $this->service = $service;
    }

    public function delete(DeleteBookingRequest $request): JsonResponse
    {
        try {
            $bookingId = $request->id;

            $result = $this->service->deleteBooking($bookingId);

            if ($result['error']) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'status'  => true,
                'message' => $result['message']
            ], 200);

        } catch (QueryException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Database error occurred.',
                'error'   => $e->getMessage()
            ], 500);

        } catch (Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Unexpected server error occurred.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
