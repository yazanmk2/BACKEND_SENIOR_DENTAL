<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateToothRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'        => 'required|exists:teeth,id',
            'descripe'  => 'required|string|max:255',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ];
    }
}
