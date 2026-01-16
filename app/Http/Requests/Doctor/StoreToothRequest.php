<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreToothRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'p_id' => ['required', 'integer', 'exists:panorama_photos,id'],
            'name' => ['required', 'string', 'max:50'],
            'number' => ['required', 'integer', 'min:1', 'max:32'],
            'descripe' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ];
    }
}
