<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisplayCaseFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_case_id' => 'required|integer|exists:display_cases,id',
            'favorite_flag'   => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'display_case_id.required' => 'Display case ID is required.',
            'favorite_flag.required'   => 'Favorite flag is required.',
            'favorite_flag.in'         => 'Favorite flag must be 0 or 1.',
        ];
    }
}
