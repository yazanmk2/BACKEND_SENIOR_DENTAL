<?php
namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DoctorInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'specialization' => 'required|in:Orthodontics,Oral Surgery,Dental Implants,Pediatric Dentistry,Cosmetic Fillings,Periodontics',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ];
    }
}