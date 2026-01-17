<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\DeleteTeethDoctorService;
use Illuminate\Http\Request;

class TeethDoctorController extends Controller
{
    public function delete(Request $request, DeleteTeethDoctorService $service)
    {
        $request->validate([
            'id' => 'required|integer|exists:teeth_doctor,id',
        ]);

        $result = $service->handle(
            $request->id,
            auth()->id()
        );

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ], $result['code']);
    }
}
