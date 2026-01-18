<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\GetTeethByPanoramaRequest;
use App\Services\Doctor\GetTeethByPanoramaService;
use Illuminate\Http\JsonResponse;

class GetTeethByPanoramaController extends Controller
{
    public function index(
        GetTeethByPanoramaRequest $request,
        GetTeethByPanoramaService $service
    ): JsonResponse {
        $result = $service->handle($request->p_id);

        if (!$result['status']) {
            return response()->json($result, 500);
        }

        return response()->json([
            'status' => true,
            'teeth' => $result['data'],
        ]);
    }
}
