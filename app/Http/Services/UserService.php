<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IUserService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data): User 
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->store($data);
    }

    public function changePassword(array $data)
    {
            $this->checkPasswordIsCorrect($data['old_password']);

            $data['password'] = Hash::make($data['new_password']);
            unset($data['old_password'], $data['new_password']);

            if(!$this->repository->updateById(auth()->user()->id, $data)) {
                throw new Exception('An error occured while updating password.', 400);
            }
    }

    public function changeEmail(array $data)
    {
            $this->checkPasswordIsCorrect($data['password']);
            unset($data['password']);

            if(!$this->repository->updateById(auth()->user()->id, $data)) {
                throw new Exception('An error occured while updating email.', 400);
            }
    }

    public function changePersonalInformations(array $data) {
        if(!$this->repository->updateById(auth()->user()->id, $data)) {
            throw new Exception('An error occured while updating personal informations.', 400);
        }
    }

    public function changeBiography(array $data) {
        if(!$this->repository->updateById(auth()->user()->id, $data)) {
            throw new Exception('An error occured while updating biography.', 400);
        }
    }

    public function deleteAccount(array $data) {
        $this->checkPasswordIsCorrect($data['password']);
        if(!$this->repository->deleteById(auth()->user()->id)) {
            throw new Exception('An error occured while updating biography.', 400);
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