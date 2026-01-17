<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:bookings,id'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Booking ID is required.',
            'id.exists'   => 'Booking does not exist.',
        ];
    }
}
