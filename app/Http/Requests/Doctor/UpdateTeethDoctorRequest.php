<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeethDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already protected by sanctum
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:teeth_doctor,id',
            'descripe' => 'required|string|max:1000',
        ];
    }
}
