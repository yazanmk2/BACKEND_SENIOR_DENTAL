<?php

namespace App\Http\Controllers\ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\ai\DetectTeethRequest;
use App\Services\ai\DetectTeethService;
use Illuminate\Database\QueryException;
use Exception;

class DetectTeethController extends Controller
{
    protected DetectTeethService $service;

    public function __construct(DetectTeethService $service)
    {
        $this->service = $service;
    }

    public function detect(DetectTeethRequest $request)
    {
        try {
            $result = $this->service->handle($request->file('image'));

            return response()->json(
                $result,
                $result['status'] ? 200 : 400
            );

        } catch (QueryException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Database error.',
                'error' => $e->getMessage()
            ], 500);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Unexpected error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
