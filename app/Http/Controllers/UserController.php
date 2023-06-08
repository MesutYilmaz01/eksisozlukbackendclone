<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeAvatarRequest;
use App\Http\Requests\ChangeBiographyRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePersonelInformationsRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Http\ServiceContracts\IUserService;
use Exception;

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

    public function changeEmail(ChangeEmailRequest $request)
    {
        try{
            $this->service->changeEmail($request->only(['email', 'password']));
            return response()->json(['message' => 'Email changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changePersonalInformations(ChangePersonelInformationsRequest $request)
    {
        try{
            $this->service->changePersonalInformations($request->only(['birthday', 'gender']));
            return response()->json(['message' => 'Informations changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changeBiography(ChangeBiographyRequest $request)
    {
        try{
            $this->service->changeBiography($request->only(['biography']));
            return response()->json(['message' => 'Biography changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        try{
            $this->service->deleteAccount($request->only(['password']));
            return response()->json(['message' => 'Account deleted successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changeAvatar(ChangeAvatarRequest $request)
    {
        try{
            $this->service->changeAvatar($request->file('image'));
            return response()->json(['message' => 'Avatar changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
