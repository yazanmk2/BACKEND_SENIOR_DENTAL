<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DeleteDisplayCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_case_id' => 'required|integer|exists:display_cases,id',
        ];
    }

    public function messages(): array
    {
        return [
            'display_case_id.required' => 'Display case ID is required.',
            'display_case_id.exists'   => 'Display case not found.',
        ];
    }
}
