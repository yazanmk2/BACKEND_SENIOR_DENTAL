<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdatePhotoService
{
    public function updatePhoto($file)
    {
        $user = Auth::user();

        // Delete old photo if exists (stored on the public disk)
        if ($user->photo) {
            // If DB stores full URL, extract the relative storage path after '/storage/'
            $oldPath = Str::startsWith($user->photo, ['http://', 'https://'])
                ? Str::after($user->photo, '/storage/')
                : $user->photo;
            Storage::disk('public')->delete($oldPath);
        }

        // Build a user-friendly, unique filename: firstname-lastname-<id>-<timestamp>.ext
        $namePart = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        $slug = $namePart !== '' ? Str::slug($namePart) : 'user';
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = $slug . '-' . $user->id . '-' . now()->format('YmdHis') . ($ext ? ('.' . $ext) : '');

        // Store new photo in the "public" disk under personal-photo/
        // Path returned like: personal-photo/firstname-lastname-id-YYYYMMDDHHMMSS.ext
        $path = $file->storeAs('personal-photo', $filename, 'public');

        // Build absolute public URL using current request host (ngrok, etc.)
        // Requires storage symlink: php artisan storage:link
        $publicUrl = url(\Illuminate\Support\Facades\Storage::url($path));

        // Persist full URL in DB
        $user->update([
            'photo' => $publicUrl,
        ]);

        return $publicUrl;
    }
}
