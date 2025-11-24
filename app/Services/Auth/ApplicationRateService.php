<?php

namespace App\Services\Auth;

use App\Models\ApplicationRateFeedback;
use Illuminate\Support\Facades\Auth;

class ApplicationRateService
{
    public function submitRate(array $validatedData)
    {
        $user = Auth::user();

        return ApplicationRateFeedback::create([
            'u_id' => $user->id,
            'rate' => $validatedData['rate'],
            'feedback' => $validatedData['feedback'] ?? null,
        ]);
    }
}
