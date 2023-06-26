<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IUserService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(array $data): User 
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        
        if(!$user) {
            throw new Exception('An error occured while creating user.', 400);
        }

        return $user;
    }

    public function changePassword(array $data)
    {
        $this->checkPasswordIsCorrect($data['old_password']);

        $data['password'] = Hash::make($data['new_password']);
        unset($data['old_password'], $data['new_password']);

        if(!$this->userRepository->update(auth()->user(), $data)) {
            throw new Exception('An error occured while updating password.', 400);
        }
    }

    public function changeEmail(array $data)
    {
        $this->checkPasswordIsCorrect($data['password']);
        unset($data['password']);

        if(!$this->userRepository->update(auth()->user(), $data)) {
            throw new Exception('An error occured while updating email.', 400);
        }
    }

    public function changePersonalInformations(array $data) {
        if(!$this->userRepository->update(auth()->user(), $data)) {
            throw new Exception('An error occured while updating personal informations.', 400);
        }
    }

    public function changeBiography(array $data) {
        if(!$this->userRepository->update(auth()->user(), $data)) {
            throw new Exception('An error occured while updating biography.', 400);
        }
    }

    public function deleteAccount(array $data) {
        $this->checkPasswordIsCorrect($data['password']);
        if(!$this->userRepository->delete(auth()->user())) {
            throw new Exception('An error occured while updating biography.', 400);
        }
    }

    public function changeAvatar($data) {
        $result = $data->store('public/images');
        if(!$result) {
            throw new Exception('An error occured while changing avatar.', 400);
        }

        if(!$this->userRepository->update(auth()->user(), ['avatar' => $result])) {
            throw new Exception('An error occured while updating avatar.', 400);
        }
    }

    /**
     * Checks if password is correct
     */
    private function checkPasswordIsCorrect($password)
    {
        if(!Hash::check($password, auth()->user()->password)) {
            throw new Exception('Old password is not correct', 400);
        }
    }

}