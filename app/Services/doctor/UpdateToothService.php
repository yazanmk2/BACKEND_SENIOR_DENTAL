<?php

namespace App\Services\Doctor;

use App\Models\Teeth;
use Illuminate\Support\Facades\Storage;

class UpdateToothService
{
    public function update(array $data): Teeth
    {
        $tooth = Teeth::findOrFail($data['id']);

        /* ===============================
           Update description
        =============================== */
        $tooth->descripe = $data['descripe'];

        /* ===============================
           Optional photo update
        =============================== */
        if (isset($data['photo'])) {

            // Delete old photo if exists
            if ($tooth->photo_panorama_generated) {
                $oldPath = str_replace(
                    url('/storage') . '/',
                    '',
                    $tooth->photo_panorama_generated
                );

                Storage::disk('public')->delete($oldPath);
            }

            $photo = $data['photo'];

            $photoName = 'tooth_edit_' . time() . '_' . uniqid() . '.' .
                $photo->getClientOriginalExtension();

            $photoPath = $photo->storeAs(
                'teeth_manual_edits',
                $photoName,
                'public'
            );

            $tooth->photo_panorama_generated =
                request()->getSchemeAndHttpHost() . '/storage/' . $photoPath;
        }

        $tooth->save();

        return $tooth;
    }
}
