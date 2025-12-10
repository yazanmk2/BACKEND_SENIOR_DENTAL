<?php
namespace App\Services\Doctor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Doctor;
use App\Models\DisplayCase;
use Illuminate\Http\JsonResponse;
class DoctorInfoService
{
    public function updateDoctorInfo($request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            $doctor = new Doctor();
            $doctor->u_id = $user->id;
        }

        // تحديث CV
        if ($request->hasFile('cv')) {
            if ($doctor->cv) {
                Storage::disk('public')->delete($doctor->cv);
            }
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $doctor->cv = $cvPath;
        }

        // تحديث باقي المعلومات
        $doctor->specialization = $request->input('specialization', $doctor->specialization);
        $doctor->previous_works = $request->input('previous_works', $doctor->previous_works);
        $doctor->open_time = $request->input('open_time', $doctor->open_time);
        $doctor->close_time = $request->input('close_time', $doctor->close_time);
        $doctor->save();

        // إذا تم رفع صور before/after
        if ($request->hasFile('photo_before') && $request->hasFile('photo_after')) {
            $beforePath = $request->file('photo_before')->store('cases/before', 'public');
            $afterPath = $request->file('photo_after')->store('cases/after', 'public');

            DisplayCase::create([
                'd_id' => $doctor->id,
                'photo_before' => $beforePath,
                'photo_after' => $afterPath,
                'favorite_flag' => false,
                'booking_id' => $request->input('booking_id'), // إذا بدك تربطي الحالة بحجز
            ]);
        }

        return $doctor;
    }

    public function storeDoctorInfo($request)
{
    $user = Auth::user();

    $cvPath = $request->file('cv')->store('cvs', 'public');

    $doctor = Doctor::create([
        'u_id' => $user->id,
        'cv' => $cvPath,
        'specialization' => $request->specialization,
        'open_time' => $request->open_time,
        'close_time' => $request->close_time,
    ]);

    return $doctor;
}

public function handleUpdateInfo($request): JsonResponse
{
    $doctor = $this->updateDoctorInfo($request);

    return response()->json([
        'message' => 'Doctor info updated successfully.',
        'doctor' => $doctor
    ]);
}
public function handleSubmitInfo($request): JsonResponse
{
    $doctor = $this->storeDoctorInfo($request);

    return response()->json([
        'message' => 'Doctor info saved successfully.',
        'doctor' => $doctor
    ]);
}

}