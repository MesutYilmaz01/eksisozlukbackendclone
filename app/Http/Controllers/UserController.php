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
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        try{
            return new UserResource($this->userService->store($request->only(['username', 'email', 'password'])));
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try{
            $this->userService->changePassword($request->only(['old_password', 'new_password']));
            return response()->json(['message' => 'Password changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        try{
            $this->userService->changeEmail($request->only(['email', 'password']));
            return response()->json(['message' => 'Email changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changePersonalInformations(ChangePersonelInformationsRequest $request)
    {
        try{
            $this->userService->changePersonalInformations($request->only(['birthday', 'gender']));
            return response()->json(['message' => 'Informations changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changeBiography(ChangeBiographyRequest $request)
    {
        try{
            $this->userService->changeBiography($request->only(['biography']));
            return response()->json(['message' => 'Biography changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        try{
            $this->userService->deleteAccount($request->only(['password']));
            return response()->json(['message' => 'Account deleted successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function changeAvatar(ChangeAvatarRequest $request)
    {
        try{
            $this->userService->changeAvatar($request->file('image'));
            return response()->json(['message' => 'Avatar changed successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function show(User $user)
    {
        return new UserResource($user->load(['followers', 'followed', 'entries', 'entries.header', 'entries.likes']));
    }
}
