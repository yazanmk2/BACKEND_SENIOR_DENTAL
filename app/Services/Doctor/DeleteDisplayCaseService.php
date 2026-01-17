<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\DisplayCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteDisplayCaseService
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

            // Get doctor from token
            $doctor = Doctor::where('u_id', $user->id)->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor profile not found.'
                ];
            }

            // Get display case owned by doctor
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

            // 🔥 Delete image files
            $this->deleteFileFromUrl($displayCase->photo_before);
            $this->deleteFileFromUrl($displayCase->photo_after);

            // Delete DB record
            $displayCase->delete();

            DB::commit();

            return [
                'status' => true,
                'message' => 'Display case deleted successfully.'
            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Failed to delete display case.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete file using full URL stored in DB
     */
    private function deleteFileFromUrl(?string $url): void
    {
        if (!$url) return;

        // Convert full URL → storage path
        $path = parse_url($url, PHP_URL_PATH);
        $path = str_replace('/storage/', '', $path);

        Storage::disk('public')->delete($path);
    }
}
