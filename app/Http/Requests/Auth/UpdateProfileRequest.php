<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'  => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'last_name'   => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255|unique:users,email,' . $this->user()->id,
            'address'     => 'nullable|string|max:500',
            'gender'      => 'nullable|string|in:male,female',
            'photo'       => 'nullable|image|max:2048',
        ];
    }
}
