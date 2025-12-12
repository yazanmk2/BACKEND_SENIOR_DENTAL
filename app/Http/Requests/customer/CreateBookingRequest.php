<?php

namespace App\Http\Requests\customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route will be protected by auth:sanctum
    }

    public function rules(): array
    {
        return [
            'd_id' => 'required|integer|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i', // 14:30 format
        ];
    }

    public function messages(): array
    {
        return [
            'd_id.required' => 'Doctor id is required.',
            'd_id.exists'   => 'Selected doctor does not exist.',

            'date.required' => 'Booking date is required.',
            'date.date'     => 'Booking date must be a valid date.',
            'date.after_or_equal' => 'Booking date cannot be in the past.',

            'time.required'    => 'Booking time is required.',
            'time.date_format' => 'Time must be in format HH:MM (24h).',
        ];
    }
}
