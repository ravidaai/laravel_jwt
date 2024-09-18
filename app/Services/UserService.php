<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Registers a new user with the provided validated data.
     *
     * @param array $validatedData An array containing the validated user data.
     * @return array An array containing the newly created user and their JWT token.
     */
    public function register(array $validatedData)
    {
        // Create user with validated data
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Encrypt the password
            'privacy_policy_agreement' => $validatedData['privacy_policy_agreement'],
        ]);

        // Generate JWT token for the user
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
