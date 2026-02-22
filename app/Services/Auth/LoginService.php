<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginService
{

    public function login(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = Auth::user()->createToken('auth_token')->plainTextToken;
        return $token;
    }

}
