<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DeleteToothRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already protected by auth middleware
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:teeth,id'],
        ];
    }
}
