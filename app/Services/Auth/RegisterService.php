<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function register(array $data)
    {
        // Create user
        $user = User::create([
            'first_name'  => $data['first_name'],
            'father_name' => $data['father_name'] ?? null,
            'last_name'   => $data['last_name'],
            'phone'       => $data['phone'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'address'     => $data['address'] ?? null,
            'gender'      => $data['gender'],
            'type'        => $data['type'],
        ]);

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
