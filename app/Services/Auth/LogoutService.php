<?php

namespace App\Services\Auth;

class LogoutService
{
    public function logout($user): void
    {
        // Revoke the current access token only
        $user->currentAccessToken()->delete();
    }
}
