<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return (new LoginResource(['token' => $token]))->response()->setStatusCode(200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return (new LoginResource(['token' => auth()->refresh()]))->response()->setStatusCode(200);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
