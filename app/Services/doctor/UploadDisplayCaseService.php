<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\DisplayCase;
use Illuminate\Support\Facades\Auth;

class UploadDisplayCaseService
{
    public function handle(array $data): array
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.'
                ];
            }

            // Get doctor by token user_id
            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // 🔥 Dynamic URL (works with ngrok, localhost, prod)
            $serverUrl = rtrim(request()->getSchemeAndHttpHost(), '/');

            /*
            |--------------------------------------------------------------------------
            | Store BEFORE image
            |--------------------------------------------------------------------------
            */
            $beforeFile = $data['photo_before'];
            $beforeName = 'before_' . time() . '_' . uniqid() . '.' . $beforeFile->getClientOriginalExtension();
            $beforeFile->storeAs('display_cases/before', $beforeName, 'public');

            $beforeUrl = $serverUrl . '/storage/display_cases/before/' . $beforeName;

            /*
            |--------------------------------------------------------------------------
            | Store AFTER image
            |--------------------------------------------------------------------------
            */
            $afterFile = $data['photo_after'];
            $afterName = 'after_' . time() . '_' . uniqid() . '.' . $afterFile->getClientOriginalExtension();
            $afterFile->storeAs('display_cases/after', $afterName, 'public');

            $afterUrl = $serverUrl . '/storage/display_cases/after/' . $afterName;

            /*
            |--------------------------------------------------------------------------
            | Save to database (FULL URL)
            |--------------------------------------------------------------------------
            */
            $displayCase = DisplayCase::create([
                'd_id'          => $doctor->id,
                'photo_before'  => $beforeUrl,
                'photo_after'   => $afterUrl,
                'favorite_flag' => 0,
            ]);

            return [
                'status' => true,
                'message' => 'Display case uploaded successfully.',
                'data' => $displayCase
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => 'Failed to upload display case.',
                'error' => $e->getMessage()
            ];
        }
    }
}
