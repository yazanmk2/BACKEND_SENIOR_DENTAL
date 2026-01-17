<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeethDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'p_id'     => 'required|integer|exists:panorama_photos_doctors,id',
            'name'     => 'required|string|max:255',
            'number'   => 'required|integer|min:1|max:32',
            'descripe' => 'nullable|string|max:1000',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ];
    }
}
