<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'   => 'required|string|max:255',
            'father_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20|unique:users,phone',
            'email'        => 'required|email|max:255|unique:users,email',
            'password'     => 'required|string|min:6',
            'address'      => 'nullable|string|max:255',
            'gender'       => ['required', Rule::in(['male', 'female'])],
            'type'         => 'required|string|max:50', // e.g. doctor, customer, admin
        ];
    }
}
