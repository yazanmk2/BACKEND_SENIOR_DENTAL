<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class GetTeethByPanoramaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth already handled by middleware
    }

    public function rules(): array
    {
        return [
            'p_id' => ['required', 'integer', 'exists:panorama_photos,id'],
        ];
    }
}
