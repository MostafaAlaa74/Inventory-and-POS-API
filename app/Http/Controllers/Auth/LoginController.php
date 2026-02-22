<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\LoginService;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }


    public function login(LoginRequest $request){
        $data = $request->validated();
        $response = $this->loginService->login($data);
        return response()->json([
            'token' => $response,
            'message' => 'Logged in successfully',
        ], 200);
    }
}
