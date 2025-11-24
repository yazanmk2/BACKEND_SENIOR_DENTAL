<?php

namespace App\Http\Requests\customer;

use Illuminate\Foundation\Http\FormRequest;

class BookingByStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'status' => 'required|string|in:pending,approved,confirmed,cancelled,rejected,completed'
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Status is required.',
            'status.string' => 'Status must be a string.',
            'status.in' => 'Invalid status value.'
        ];
    }
}
