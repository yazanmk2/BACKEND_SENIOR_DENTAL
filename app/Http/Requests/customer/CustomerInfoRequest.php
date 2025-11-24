<?php

namespace App\Http\Requests\customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // token authentication will protect it
    }

    public function rules(): array
    {
        return [
            'birthdate' => 'required|date',
            'patient_record' => 'nullable|file|mimes:pdf|max:4096', // 2 MB max
            'patient_record_text' => 'nullable|string',
        ];
    }
}
