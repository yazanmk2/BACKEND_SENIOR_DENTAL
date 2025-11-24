<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeletePhotoService
{
    public function deletePhoto()
    {
        $user = Auth::user();

        // No photo exists
        if (!$user->photo) {
            return [
                'status' => false,
                'message' => 'No photo found for this user.',
                'deleted' => false
            ];
        }

        // Extract filename from stored URL
        // Example stored: https://domain.com/storage/personal-photo/Yazan_Maksoud_181.jpg
        $filename = basename($user->photo);

        // Full path in storage
        $filePath = 'public/personal-photo/' . $filename;

        // Delete the file if exists
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Remove photo URL from DB
        $user->update([
            'photo' => null,
        ]);

        return [
            'status' => true,
            'message' => 'Photo deleted successfully.',
            'deleted' => true
        ];
    }
}
