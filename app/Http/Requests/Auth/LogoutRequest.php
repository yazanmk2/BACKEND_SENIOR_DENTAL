<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Must be authenticated to reach this endpoint
    }

    public function rules(): array
    {
        return []; // No input needed for logout
    }
}
