<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\PanoramaTeethRequest;
use App\Services\doctor\PanoramaTeethService;
use Illuminate\Database\QueryException;
use Exception;

class PanoramaTeethController extends Controller
{
    protected $service;

    public function __construct(PanoramaTeethService $service)
    {
        $this->service = $service;
    }

    public function show(PanoramaTeethRequest $request)
    {
        try {
            $result = $this->service->getLatestPanoramaWithTeeth(
                $request->customer_id
            );

            if (isset($result['error']) && $result['error']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'data' => null
                ], 403);
            }

            return response()->json([
                'status' => true,
                'message' => 'Panorama and teeth retrieved successfully.',
                'data' => $result['data']
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unexpected server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
