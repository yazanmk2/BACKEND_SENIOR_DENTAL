<?php

namespace App\Http\Requests\ai;

use Illuminate\Foundation\Http\FormRequest;

class DetectTeethRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ];
    }
}
