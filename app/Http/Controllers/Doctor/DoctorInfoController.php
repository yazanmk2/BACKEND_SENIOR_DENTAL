<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\UpdateDoctorInfoRequest;
use App\Services\Doctor\DoctorInfoService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Doctor\DoctorInfoRequest;


class DoctorInfoController extends Controller
{
    protected $infoService;

    public function __construct(DoctorInfoService $infoService)
    {
        $this->infoService = $infoService;
    }

   public function update(UpdateDoctorInfoRequest $request): JsonResponse
{
    return $this->infoService->handleUpdateInfo($request);
}

    public function submitInfo(DoctorInfoRequest $request): JsonResponse
{
    return $this->infoService->handleSubmitInfo($request);
}

}