<?php

namespace App\Http\Requests\Ai;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DiagnoseOrthodonticsRequest extends FormRequest
{
    /**
     * Authorize the request
     */
    public function authorize(): bool
    {
        return true; // already protected by auth:sanctum
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10240', // 10 MB
            ],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Panorama image is required.',
            'image.image'    => 'The uploaded file must be a valid image.',
            'image.mimes'    => 'Only JPG and PNG images are allowed.',
            'image.max'      => 'Image size must not exceed 10MB.',
        ];
    }

    /**
     * Force JSON response on validation error (API safe)
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
