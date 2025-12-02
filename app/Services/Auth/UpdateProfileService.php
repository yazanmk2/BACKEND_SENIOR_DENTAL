<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class UpdateProfileService
{
    public function update(array $data)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'User not authenticated.',
                    'data' => null
                ];
            }

            if (empty($data)) {
                return [
                    'status' => false,
                    'message' => 'No data provided to update.',
                    'data' => null
                ];
            }

            // Update only fields sent in the request
            foreach ($data as $key => $value) {
                if ($value !== null) {      // allow null if you want, just remove this condition
                    $user->$key = $value;
                }
            }

            $user->save();

            return [
                'status' => true,
                'message' => 'Profile updated successfully.',
                'data' => $user
            ];

        } catch (\Exception $e) {

            return [
                'status'  => false,
                'message' => 'Failed to update profile.',
                'error'   => $e->getMessage()
            ];
        }
    }
}
