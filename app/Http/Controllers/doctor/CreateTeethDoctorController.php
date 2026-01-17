<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\TeethDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateTeethDoctorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'p_id'     => 'required|exists:panorama_photos_doctors,id',
            'name'     => 'required|string',
            'number'   => 'required|integer',
            'descripe' => 'nullable|string',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:4096', // ✅ optional
        ]);

        /* ===============================
           Get doctor from token
        =============================== */
        $doctor = Doctor::where('u_id', Auth::id())->first();

        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found.'
            ], 404);
        }

        /* ===============================
           Handle optional photo
        =============================== */
        $photoUrl = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store(
                'teeth_doctor/panorama_' . $request->p_id,
                'public'
            );

            $photoUrl =' request()->getSchemeAndHttpHost() . '/storage/' . $photoPath';
        }

        /* ===============================
           Create record
        =============================== */
        $tooth = TeethDoctor::create([
            'p_id'  => $request->p_id,
            'name'  => $request->name,
            'number'=> $request->number,
            'descripe' => $request->descripe,
            'photo_panorama_generated' => $photoUrl, // ✅ null if no photo
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tooth created successfully.',
            'data' => $tooth
        ], 201);
    }
}
