<?php
namespace App\Http\Requests\doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'status' => 'required|in:accepted,cancelled,done',
        'note' => 'required_if:status,done|string|nullable',
    ];
}
    public function messages(): array
    {
        return [
            'status.required' => 'Please provide a status.',
            'status.in' => 'Status must be either accepted or cancelled.',
        ];
    }
}