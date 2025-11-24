<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow authenticated users
    }

    public function rules()
    {
        return [
            'rate' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ];
    }
}
