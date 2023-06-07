<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Http\ServiceContracts\IUserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(IUserService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        return new UserResource($this->service->store($request->only(['username', 'email', 'password'])));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try{
            $this->service->changePassword($request->only(['old_password', 'new_password']));
            return response()->json(['message' => 'Password changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

}
