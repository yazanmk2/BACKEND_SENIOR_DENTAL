<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\DisplayCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateDisplayCaseFavoriteService
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

            // Get doctor by token
            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // Get the display case (must belong to this doctor)
            $displayCase = DisplayCase::where('id', $data['display_case_id'])
                ->where('d_id', $doctor->id)
                ->first();

            if (!$displayCase) {
                return [
                    'status' => false,
                    'message' => 'Display case not found or not owned by this doctor.'
                ];
            }

            DB::beginTransaction();

            // 🔥 If setting this case as favorite
            if ((int)$data['favorite_flag'] === 1) {

                // Set ALL other cases to 0
                DisplayCase::where('d_id', $doctor->id)
                    ->update(['favorite_flag' => 0]);

                // Set selected case to 1
                $displayCase->favorite_flag = 1;
                $displayCase->save();

            } else {
                // Just unset this case
                $displayCase->favorite_flag = 0;
                $displayCase->save();
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Favorite display case updated successfully.',
                'data' => $displayCase
            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Failed to update favorite display case.',
                'error' => $e->getMessage()
            ];
        }
    }
}
