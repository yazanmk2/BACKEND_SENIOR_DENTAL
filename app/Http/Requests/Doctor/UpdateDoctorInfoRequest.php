<?php

namespace App\Http\Requests\Doctor;

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
            'cv'             => 'nullable|file|mimes:pdf|max:5120', // 5MB
            'specialization' => 'required|string|max:255',
            'previous_works' => 'nullable|string',
            'open_time'      => 'required|date_format:H:i',
            'close_time'     => 'required|date_format:H:i|after:open_time',
        ];
    }

    public function messages(): array
    {
        return [
            'cv.mimes' => 'CV must be a PDF file.',
            'close_time.after' => 'Close time must be after open time.',
        ];
    }
}
