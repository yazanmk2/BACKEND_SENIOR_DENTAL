<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDisplayCaseFavoriteRequest;
use App\Services\Doctor\UpdateDisplayCaseFavoriteService;
use Illuminate\Http\JsonResponse;

class UpdateDisplayCaseFavoriteController extends Controller
{
    protected UpdateDisplayCaseFavoriteService $service;

    public function __construct(UpdateDisplayCaseFavoriteService $service)
    {
        $this->service = $service;
    }

    public function update(UpdateDisplayCaseFavoriteRequest $request): JsonResponse
    {
        $response = $this->service->handle($request->validated());

        return response()->json(
            $response,
            $response['status'] ? 200 : 400
        );
    }
}
