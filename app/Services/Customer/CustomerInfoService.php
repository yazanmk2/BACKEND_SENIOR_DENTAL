<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CustomerInfoService
{
    public function updateInfo(array $data)
    {
        $user = Auth::user();
        $customer = Customer::where('u_id', $user->id)->first();

        if (! $customer) {
            $customer = new Customer(['u_id' => $user->id]);
        }

        // Handle file upload (PDF)
        if (isset($data['patient_record'])) {
            // Build filename: patient_record_First_Last.pdf
            $fileExt = $data['patient_record']->getClientOriginalExtension();
            $fileName = 'patient_record_' . $user->first_name . '_' . $user->last_name . '.' . $fileExt;

            // Store in /storage/app/public/patient_records/
            $path = $data['patient_record']->storeAs('patient_records', $fileName, 'public');
            $customer->patient_record = $path;
        } 
        // Handle text-based patient record
        elseif (isset($data['patient_record_text'])) {
            $fileName = 'patient_record_' . $user->first_name . '_' . $user->last_name . '.txt';
            Storage::disk('public')->put('patient_records/' . $fileName, $data['patient_record_text']);
            $customer->patient_record = 'patient_records/' . $fileName;
        }

        // Save birthdate
        $customer->birthdate = $data['birthdate'];
        $customer->save();

        return $customer;
    }
}
