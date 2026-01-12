<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UploadDisplayCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo_before' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'photo_after'  => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'photo_before.required' => 'Photo before is required.',
            'photo_after.required'  => 'Photo after is required.',
            'photo_before.image'    => 'Photo before must be an image.',
            'photo_after.image'     => 'Photo after must be an image.',
        ];
    }
}
