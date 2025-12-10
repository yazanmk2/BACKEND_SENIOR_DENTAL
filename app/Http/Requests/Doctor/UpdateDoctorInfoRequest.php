<?php

namespace App\Http\Requests\doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'specialization' => 'nullable|string|max:255',
            'previous_works' => 'nullable|string',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'photo_before' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'photo_after' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'booking_id' => 'nullable|exists:bookings,id',
        ];
    }

    public function messages(): array
    {
        return [
            'cv.mimes' => 'CV must be a PDF or Word document.',
            'cv.max' => 'CV must be less than 2MB.',
            'open_time.date_format' => 'Open time must be in HH:MM format.',
            'close_time.date_format' => 'Close time must be in HH:MM format.',
            'photo_before.image' => 'Before image must be a valid image file.',
            'photo_after.image' => 'After image must be a valid image file.',
        ];
    }
}